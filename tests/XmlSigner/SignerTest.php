<?php

namespace XmlSigner\Tests;

use XmlSigner\Certificate;
use XmlSigner\Signer;

final class SignerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException XmlSigner\Exception\SignerException
     * @expectedExceptionMessage The content is not a valid XML.
     */
    public function testInvalidContent()
    {
        $certificate = $this->certificate();
        $signer = new Signer($certificate);

        $content = '';
        $tagName = 'main';
        $signer->xml($content, $tagName);
    }

    /**
     * @expectedException XmlSigner\Exception\SignerException
     * @expectedExceptionMessage The specified tag ThirdElement was not found.
     */
    public function testTagNotFound()
    {
        $certificate = $this->certificate();
        $signer = new Signer($certificate);

        $content = $this->content();
        $tagName = 'ThirdElement';
        $signer->xml($content, $tagName);
    }

    public function testSignXml()
    {
        $certificate = $this->certificate();
        $signer = new Signer($certificate);

        $content = $this->content();
        $tagName = 'DocumentElement';
        $got = $signer->xml($content, $tagName);
        $this->assertContains('<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">', $got);
        $this->assertContains('<SignedInfo>', $got);
        $this->assertContains('<CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>', $got);
        $this->assertContains('<SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>', $got);
        $this->assertContains('<Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>', $got);
        $this->assertContains('</Transforms>', $got);
        $this->assertContains('<DigestMethod', $got);
        $this->assertContains('Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>', $got);
        $this->assertContains('<DigestValue>', $got);
        $this->assertContains('<X509Certificate>', $got);
        $this->assertContains('</X509Certificate>', $got);
        $this->assertContains('</X509Data>', $got);
        $this->assertContains('</KeyInfo>', $got);
        $this->assertContains('</Signature>', $got);
    }

    private function certificate()
    {
        $pfx = file_get_contents('tests/resources/cert.pfx');
        return Certificate::readPfx($pfx, 'associacao');
    }

    private function content()
    {
        return file_get_contents('tests/resources/valid.xml');
    }
}
