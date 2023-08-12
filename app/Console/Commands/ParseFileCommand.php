<?php

namespace App\Console\Commands;

use App\Console\Processors\FileProcessor;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ParseFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan {имя команды} {путь_к_файлу_погоды}
     * php artisan {имя команды} -d {путь_к_директории_с_файлами}
     *
     * @var string
     */
    protected $signature = 'app:parse-file {path?} {--d=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse files containing weather data';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        $this->info('Parsing files...');

        $path = $this->argument('path');
        $dir = $this->option('d');

        $fileProcessor = new FileProcessor(true);
        if ($dir) {
            $data = $fileProcessor->processDirectory($dir);
        } else if (File::isFile($path)) {
            $data = $fileProcessor->processSeparateFile($path);
        } else {
            $this->error('Please provide correct path to file or directory!');
            return;
        }

        $this->comment(json_encode($data));
        $this->info('Done!');
    }
}
