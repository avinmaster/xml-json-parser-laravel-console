<?php

namespace App\Console\Processors;

use App\Console\Extractors\GzFileExtractor;
use App\Console\Extractors\ZipFileExtractor;
use App\Console\Parsers\JsonFileParser;
use App\Console\Parsers\XmlFileParser;
use Exception;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Color;

class FileProcessor
{
    private bool $log;
    private Color $red;
    private Color $green;

    public function __construct($log = false)
    {
        $this->log = $log;
        // init Color class
        $this->red = new Color('red');
        $this->green = new Color('green');
    }

    public function processSeparateFile(string $path): array
    {
        if ($this->log) echo 'Processing file ' . $path . ': ';

        $format = pathinfo($path, PATHINFO_EXTENSION);
        $fileContent = File::get($path);

        if ($format === 'zip') {
            $extractedFileContents = ZipFileExtractor::extract($path);
            $data = $this->processMultipleFileContents($extractedFileContents);
        } else if ($format === 'gz') {
            $extractedFileContents = GzFileExtractor::extract($path);
            $data = $this->processMultipleFileContents($extractedFileContents);
        } else {
            $data = $this->processFileContent($fileContent, $format);
        }

        if ($this->log) {
            if (!$data) {
                echo $this->red->apply('file format is not supported (' . $format . ') or corrupted') . PHP_EOL;
            } else {
                echo $this->green->apply('file is processed successfully') . PHP_EOL;
            }
        }

        return $data;
    }

    public function processDirectory(array $directories): array
    {
        $data = [];
        foreach ($directories as $directory) {
            try {
                $files = File::files($directory);
            } catch (Exception $e) {
                echo $this->red->apply('Error: ' . $e->getMessage()) . PHP_EOL;
                continue;
            }
            $data = $this->processMultipleFiles($files);
        }

        return $data;
    }

    public function processMultipleFiles(array $files): array
    {
        $data = [];
        foreach ($files as $file) {
            $data = array_merge($data, $this->processSeparateFile($file));
        }

        return $data;
    }

    public function processMultipleFileContents(array $fileContents): array
    {
        $data = [];
        foreach ($fileContents as $fileContent) {
            $data = array_merge($data, $this->processFileContent($fileContent['content'], $fileContent['format']));
        }

        return $data;
    }

    public function processFileContent(string $fileContent, string $format): array
    {
        $data = [];
        switch ($format) {
            case 'json':
                $data = JsonFileParser::parse($fileContent);
                break;
            case 'xml':
                $data = XmlFileParser::parse($fileContent);
                break;
        }

        return $data;
    }
}
