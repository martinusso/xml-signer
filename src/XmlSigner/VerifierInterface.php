<?php

namespace XmlSigner;

interface VerifierInterface
{
    /**
     * Verify signature
     * @param string $data
     * @param string $signature
     * @param int $algorithm [optional] For more information see the list of Signature Algorithms.
     * @return bool Returns true if the signature is correct, false if it is incorrect
     * @throws CertificateException An error has occurred when verify signature
     */
    function verify($data, $signature, $algorithm = OPENSSL_ALGO_SHA1);
}
