<?php

namespace App\Console\Extractors;

class GzFileExtractor
{
    public static function extract(string $path): array
    {
        try {
            $gzFileContent = gzdecode(file_get_contents($path));
        } catch (\Exception $e) {
            return [];
        }
        // detect if the file is json or xml
        $fileFormat = str_starts_with($gzFileContent, '{') ? 'json' : 'xml';

        return [
            [
                'content' => $gzFileContent,
                'format' => $fileFormat
            ]
        ];
    }
}
