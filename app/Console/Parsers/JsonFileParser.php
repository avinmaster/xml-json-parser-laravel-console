<?php

namespace App\Console\Parsers;

use Illuminate\Support\Facades\File;

class JsonFileParser
{
    public static function parse(string $file): array
    {
        $jsonFileContent = json_decode($file);

        if ($jsonFileContent) {
            return [$jsonFileContent];
        } else {
            return [];
        }
    }
}
