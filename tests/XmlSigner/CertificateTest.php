<?php

namespace XmlSigner\Tests;

use XmlSigner\Certificate;

final class CertificateTest extends \PHPUnit\Framework\TestCase
{
    public function testInvalidPrivateKey()
    {
        $needle = 'Unable to read certificate, get follow error:';
        $content = $this->getContentCertPfx();

        $fail = false;
        try {
            $cert = Certificate::readPfx($content, 'invalid_password');
        } catch (\XmlSigner\Exception\CertificateException $e) {
            $fail = true;
            $pos = strpos($e->getMessage(), $needle);
            $this->assertSame(0, $pos, 'unexpected exception message');
        }

        (!$fail) && $this->fail("No Exceptions were thrown.");
    }

    public function testReadPfx()
    {
        $dataToSign = 'Data to sign';
        $expectedSignature = 'BNlfrfWIdAHDh5aVWByf6s2t0s27+lCB1A7qtsazXQbYDg9qf20zawh46KhHOQGTrU8DEvmbYMRwqgCEvxTxk66XQF/p9ChSeILkre+h+JqyJvVGOgXi81w1Sw04jBbcSZeCl5fhESMXUcSmtVzm7oQCkygqXfCGqvpygEWS7yk=';
        $content = $this->getContentCertPfx();

        $cert = Certificate::readPfx($content, 'associacao');
        $this->assertInstanceOf(Certificate::class, $cert);

        $signature = $cert->sign($dataToSign);
        $this->assertEquals($expectedSignature, base64_encode($signature));

        $verified = $cert->verify($dataToSign, $signature);
        $this->assertTrue($verified);
    }

    private function getContentCertPfx()
    {
        return file_get_contents('tests/resources/cert.pfx');
    }
}
