<?php

namespace XmlSigner\Exception;

class CertificateException extends \RuntimeException implements ExceptionInterface
{
    public static function invalidSignature()
    {
        return new static(
            'An error has occurred when verify signature, ' . self::openSSLError()
        );
    }

    public static function privateKey()
    {
        return new static(
            'An error has occurred when get private key, ' . self::openSSLError()
        );
    }

    public static function publicKey()
    {
        return new static(
            'An error has occurred when read public key, ' . self::openSSLError()
        );
    }

    public static function signContent()
    {
        return new static(
            'An unexpected error has occurred when sign a content, ' . self::openSSLError()
        );
    }

    public static function unableToRead()
    {
        return new static(
            'Unable to read certificate, ' . self::openSSLError()
        );
    }

    private static function openSSLError()
    {
        $error = 'get follow error: ';
        while ($msg = openssl_error_string()) {
            $error .= "($msg)";
        }
        return $error;
    }
}
