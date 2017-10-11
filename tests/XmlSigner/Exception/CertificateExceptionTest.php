<?php

namespace XmlSigner\Tests;

use XmlSigner\Exception\CertificateException;

final class CertificateExceptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException XmlSigner\Exception\CertificateException
     * @expectedExceptionMessage An error has occurred when verify signature, get follow error:
     */
    public function testInvalidSignature()
    {
        throw CertificateException::invalidSignature();
    }

    /**
     * @expectedException XmlSigner\Exception\CertificateException
     * @expectedExceptionMessage An error has occurred when get private key, get follow error:
     */
    public function testPrivateKey()
    {
        throw CertificateException::privateKey();
    }

    /**
     * @expectedException XmlSigner\Exception\CertificateException
     * @expectedExceptionMessage An error has occurred when read public key, get follow error:
     */
    public function testPublicKey()
    {
        throw CertificateException::publicKey();
    }

    /**
     * @expectedException XmlSigner\Exception\CertificateException
     * @expectedExceptionMessage An unexpected error has occurred when sign a content, get follow error:
     */
    public function testSignContent()
    {
        throw CertificateException::signContent();
    }
}
