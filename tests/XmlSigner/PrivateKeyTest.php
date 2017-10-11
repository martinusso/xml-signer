<?php

namespace XmlSigner\Tests;

use XmlSigner\PrivateKey;

final class PrivateKeyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @expectedException XmlSigner\Exception\CertificateException
     * @expectedExceptionMessage An error has occurred when get private key, get follow error:
     */
    public function testInvalidPrivateKey()
    {
        $r = new PrivateKey('invalid');
    }

    public function testSignData()
    {
        $expected = 'd9qMgSMWxfQNgZniebSReyM0FXORQbArXkU6TzBVdoILjrbbWkU4J+4Jpop4Dxq54AGon0Q+5V9Kj5g/a6XqIQhix/Gs2tH6Rete+zWoXQjldevQq7SFiVZPv7MaiQI6wCPyQkC8h5zcHH9eFHzuhB8BRm+CJ0c9JKtYvwLiNLE=';
        $privateKey = $this->privateKey();
        $signature = (new PrivateKey($privateKey))->sign('The string of data you wish to sign');
        $this->assertEquals($expected, base64_encode($signature));
    }

    private function privateKey()
    {
        $pk = 'tests/resources/private_key.pem';
        return file_get_contents($pk);
    }
}
