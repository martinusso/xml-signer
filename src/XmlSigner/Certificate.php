<?php

namespace XmlSigner;

use XmlSigner\Exception\CertificateException;

class Certificate implements SignerInterface, VerifierInterface
{
    /**
     * Read a PFX certificate and return this class
     * @param string $content
     * @param string $password
     * @return \static
     * @throws CertificateException
     */
    public static function readPfx($content, $password)
    {
        $certs = [];
        if (!openssl_pkcs12_read($content, $certs, $password)) {
            throw CertificateException::unableToRead();
        }
        $chain = '';
        if (!empty($certs['extracerts'])) {
            foreach ($certs['extracerts'] as $ec) {
                $chain .= $ec;
            }
        }
        return new Certificate(
            new PrivateKey($certs['pkey']),
            new PublicKey($certs['cert']),
            $chain
        );
    }

    private $privateKey;
    private $publicKey;
    private $chainKeysString;

    public function __construct(
        PrivateKey $privateKey,
        PublicKey $publicKey,
        $chainKeysString = null
    ) {
        $this->privateKey = $privateKey;
        $this->publicKey = $publicKey;
        $this->chainKeysString = $chainKeysString;
        // $this->chainKeys = $chainKeys;
    }

    /**
     * {@inheritdoc}
     */
    public function sign($content, $algorithm = OPENSSL_ALGO_SHA1)
    {
        return $this->privateKey->sign($content, $algorithm);
    }

    public function verify($data, $signature, $algorithm = OPENSSL_ALGO_SHA1)
    {
        return $this->publicKey->verify($data, $signature, $algorithm);
    }
}
