<?php

namespace App\Console\Extractors;

class ZipFileExtractor
{
    public static function extract(string $path): array
    {
        $zipArchive = new \ZipArchive();
        $zipArchive->open($path);
        $extractedFiles = [];

        for ($i = 0; $i < $zipArchive->numFiles; $i++) {
            // get file content and format
            $fileContent = $zipArchive->getFromIndex($i);
            $fileFormat = $zipArchive->getNameIndex($i);
            $fileFormat = pathinfo($fileFormat, PATHINFO_EXTENSION);

            $extractedFiles[] = [
                'content' => $fileContent,
                'format' => $fileFormat
            ];
        }

        return $extractedFiles;
    }
}
