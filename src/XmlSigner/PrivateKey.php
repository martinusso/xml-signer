<?php

namespace XmlSigner;

use XmlSigner\Exception\CertificateException;

class PrivateKey implements SignerInterface
{
    private $privateKey;
    private $resource;

    public function __construct(string $privateKey)
    {
        $this->privateKey = $privateKey;
        $this->read();
    }

    private function read()
    {
        if (!$resource = openssl_pkey_get_private($this->privateKey)) {
            throw CertificateException::privateKey();
        }
        $this->resource = $resource;
    }

    /**
     * {@inheritdoc}
     */
    public function sign($content, $algorithm = OPENSSL_ALGO_SHA1)
    {
        $encryptedData = '';
        if (!openssl_sign($content, $encryptedData, $this->resource, $algorithm)) {
            throw CertificateException::signContent();
        }
        return $encryptedData;
    }
}
