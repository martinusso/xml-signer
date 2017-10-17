<?php

namespace XmlSigner\Tests;

use XmlSigner\Validator\XmlValidator;

final class XmlValidatorTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyContent()
    {
        $got = XmlValidator::valid('');
        $this->assertFalse($got);
    }

    public function testInvalidXml()
    {
        $content =
            '<DocumentElement param="value">
                <FirstElement>
                    Some Text
                </FirstElement>
                <SecondElement param2="something">
                    Pre-Text <Inline>Inlined text</Inline> Post-text.
                </SecondElement>';
        $got = XmlValidator::valid($content);
        $this->assertFalse($got);
    }

    public function testValidContent()
    {
        $xmlContent =
            '<?xml version="1.0" encoding="UTF-8"?>
            <DocumentElement param="value">
                <FirstElement>
                    Some Text
                </FirstElement>
                <SecondElement param2="something">
                    Something else
                </SecondElement>
            </DocumentElement>';
        $got = XmlValidator::valid($xmlContent);
        $this->assertTrue($got);
    }
}
