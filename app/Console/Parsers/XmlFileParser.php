<?php

namespace App\Console\Parsers;

class XmlFileParser
{
    public static function parse(string $content): array
    {
        libxml_use_internal_errors(true);
        $xmlFileContent = simplexml_load_string($content);
        if ($xmlFileContent) {
            return [$xmlFileContent];
        } else {
            return [];
        }
    }
}
