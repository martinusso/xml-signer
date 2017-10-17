<?php

namespace XmlSigner\Exception;

class SignerException extends \RuntimeException implements ExceptionInterface
{
    public static function invalidContent()
    {
        return new static(
            'The content is not a valid XML.'
        );
    }

    public static function tagNotFound($tagName)
    {
        return new static(
            "The specified tag $tagName was not found."
        );
    }
}
