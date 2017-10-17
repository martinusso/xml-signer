<?php

namespace XmlSigner\Validator;

class XmlValidator
{
    public static function valid($content)
    {
        $content = trim($content);
        if (empty($content)) {
            return false;
        }
        
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        simplexml_load_string($content);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        return empty($errors);
    }
}
