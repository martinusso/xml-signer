<?php

namespace XmlSigner;

class PublicKey implements VerifierInterface
{
    const SIGNATURE_CORRECT = 1;
    const SIGNATURE_INCORRECT = 0;
    const SIGNATURE_ERROR = -1;

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function verify($data, $signature, $algorithm = OPENSSL_ALGO_SHA1)
    {
        $verified = openssl_verify($data, $signature, $this->key, $algorithm);
        if ($verified === self::SIGNATURE_ERROR) {
            throw CertificateException::invalidSignature();
        }
        return $verified === self::SIGNATURE_CORRECT;
    }

    /**
     * Returns unformated public key
     * @return string
     */
    public function unformated()
    {
        $ret = preg_replace('/-----.*[\n]?/', '', $this->key);
        return preg_replace('/[\n\r]/', '', $ret);
    }
}
