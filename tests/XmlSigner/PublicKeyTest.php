<?php

namespace XmlSigner\Tests;

use XmlSigner\PublicKey;

final class PublicKeyTest extends \PHPUnit\Framework\TestCase
{
    public function testVerifyInvalidSignature()
    {
        $signature = 'd9qMgSMWxfQNgZniebSReyM0FXORQbArXkU6TzBVdoILjrbbWkU4J+';
        $publicKey = $this->publicKey();
        $verified = (new PublicKey($publicKey))->verify('The string of data you wish to sign', base64_decode($signature));
        $this->assertFalse($verified);
    }

    public function testVerify()
    {
        $signature = 'd9qMgSMWxfQNgZniebSReyM0FXORQbArXkU6TzBVdoILjrbbWkU4J+4Jpop4Dxq54AGon0Q+5V9Kj5g/a6XqIQhix/Gs2tH6Rete+zWoXQjldevQq7SFiVZPv7MaiQI6wCPyQkC8h5zcHH9eFHzuhB8BRm+CJ0c9JKtYvwLiNLE=';
        $publicKey = $this->publicKey();
        $verified = (new PublicKey($publicKey))->verify('The string of data you wish to sign', base64_decode($signature));
        $this->assertTrue($verified);
    }

    private function publicKey()
    {
        $pk = 'tests/resources/public_key.pem';
        return file_get_contents($pk);
    }
}
