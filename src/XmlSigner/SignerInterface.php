<?php

namespace XmlSigner;

interface SignerInterface
{
    /**
     * Generate signature
     * @param string $content The string of data you wish to sign
     * @param int $algorithm Signature Algorithm
     * @return string Returns the signature data
     * @throws CertificateException
     */
    public function sign($content, $algorithm = OPENSSL_ALGO_SHA1);
}
