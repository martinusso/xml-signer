<?php

namespace XmlSigner;

use XmlSigner\Exception\SignerException;
use XmlSigner\Validator\XmlValidator;
use DOMDocument;
use DOMNode;

class Signer
{
    const XMLDSIGNS = 'http://www.w3.org/2000/09/xmldsig#';
    const C14N = 'http://www.w3.org/TR/2001/REC-xml-c14n-20010315';
    const SHA1 = 'http://www.w3.org/2000/09/xmldsig#sha1';
    const SHA1_SIG = 'http://www.w3.org/2000/09/xmldsig#rsa-sha1';
    const SHA256 = 'http://www.w3.org/2001/04/xmlenc#sha256';
    const SHA256_SIG = 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256';

    private $algorithm;
    private $canonical;
    private $certificate;

    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    public function xml(
        $content,
        $tagName,
        $rootName = '',
        $algorithm = OPENSSL_ALGO_SHA1,
        $canonical = [true, false, null, null]
    ) {
        $this->assertValidContent($content);
        $this->algorithm = $algorithm;
        $this->canonical = $canonical;

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->loadXML($content);
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $root = $dom->documentElement;
        if (!empty($rootName)) {
            $root = $dom->getElementsByTagName($rootName)->item(0);
        }
        $node = $dom->getElementsByTagName($tagName)->item(0);
        if (empty($node) || empty($root)) {
            throw SignerException::tagNotFound($tagName);
        }
        $dom = $this->createSignature(
            $dom,
            $root,
            $node
        );
        return (string) '<?xml version="1.0" encoding="UTF-8"?>'
           . $dom->saveXML($dom->documentElement, LIBXML_NOXMLDECL);
    }

    private function assertValidContent($content)
    {
        if (!XmlValidator::valid($content)) {
            throw SignerException::invalidContent();
        }
    }

    private function createSignature(
        DOMDocument $dom,
        DOMNode $root,
        DOMNode $node
    ) {
        $algorithmData = $this->algorithmData();
        $nsSignatureMethod = $algorithmData['nsSignatureMethod'];
        $nsDigestMethod = $algorithmData['nsDigestMethod'];
        $digestAlgorithm = $algorithmData['digestAlgorithm'];

        $digestValue = $this->makeDigest($node, $digestAlgorithm);
        $signatureNode = $dom->createElementNS(self::XMLDSIGNS, 'Signature');
        $root->appendChild($signatureNode);

        $signedInfoNode = $dom->createElement('SignedInfo');
        $signatureNode->appendChild($signedInfoNode);
        $canonicalNode = $dom->createElement('CanonicalizationMethod');
        $signedInfoNode->appendChild($canonicalNode);
        $canonicalNode->setAttribute('Algorithm', self::C14N);
        $signatureMethodNode = $dom->createElement('SignatureMethod');
        $signedInfoNode->appendChild($signatureMethodNode);
        $signatureMethodNode->setAttribute('Algorithm', $nsSignatureMethod);
        $referenceNode = $dom->createElement('Reference');
        $signedInfoNode->appendChild($referenceNode);
        $transformsNode = $dom->createElement('Transforms');
        $referenceNode->appendChild($transformsNode);
        $transfNode1 = $dom->createElement('Transform');
        $transformsNode->appendChild($transfNode1);
        $transfNode1->setAttribute('Algorithm', self::XMLDSIGNS.'enveloped-signature');
        $transfNode2 = $dom->createElement('Transform');
        $transformsNode->appendChild($transfNode2);
        $transfNode2->setAttribute('Algorithm', self::C14N);
        $digestMethodNode = $dom->createElement('DigestMethod');
        $referenceNode->appendChild($digestMethodNode);
        $digestMethodNode->setAttribute('Algorithm', $nsDigestMethod);
        $digestValueNode = $dom->createElement('DigestValue', $digestValue);
        $referenceNode->appendChild($digestValueNode);

        $c14n = $this->canonize($signedInfoNode);
        $signature = $this->certificate->sign($c14n, $this->algorithm);
        $signatureValue = base64_encode($signature);
        $signatureValueNode = $dom->createElement('SignatureValue', $signatureValue);
        $signatureNode->appendChild($signatureValueNode);

        $keyInfoNode = $dom->createElement('KeyInfo');
        $signatureNode->appendChild($keyInfoNode);
        $x509DataNode = $dom->createElement('X509Data');
        $keyInfoNode->appendChild($x509DataNode);
        $pubKeyClean = $this->certificate->publicKey();
        $x509CertificateNode = $dom->createElement('X509Certificate', $pubKeyClean);
        $x509DataNode->appendChild($x509CertificateNode);

        return $dom;
    }

    private function algorithmData()
    {
        switch ($this->algorithm) {
            case OPENSSL_ALGO_SHA256:
                $digestAlgorithm = 'sha256';
                $nsSignatureMethod = self::SHA256_SIG;
                $nsDigestMethod = self::SHA256;
                break;
            default:
                $nsSignatureMethod = self::SHA1_SIG;
                $nsDigestMethod = self::SHA1;
                $digestAlgorithm = 'sha1';
                break;
        }

        return [
            'digestAlgorithm' => $digestAlgorithm,
            'nsSignatureMethod' => $nsSignatureMethod,
            'nsDigestMethod' => $nsDigestMethod
        ];
    }

    /**
    * Calculate digest value for given node
    * @param DOMNode $node
    * @param string $algorithm
    * @return string
    */
    private function makeDigest(DOMNode $node, $algorithm)
    {
        $c14n = $this->canonize($node);
        $hashValue = hash($algorithm, $c14n, true);
        return base64_encode($hashValue);
    }

    /**
    * Reduced to the canonical form
    * @param DOMNode $node
    * @return string
    */
    private function canonize(DOMNode $node)
    {
        return $node->C14N(
            $this->canonical[0],
            $this->canonical[1],
            $this->canonical[2],
            $this->canonical[3]
        );
    }
}
