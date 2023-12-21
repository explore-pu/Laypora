<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class KeyGenerate extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Set the application key";

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->getRandomKey();

        $path = base_path('.env');

        if (file_exists($path)) {
            file_put_contents($path, str_replace('APP_KEY=' . env('APP_KEY'), 'APP_KEY=' . $key, file_get_contents($path)));
        }

        $this->info("Application key set successfully.");
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function getRandomKey()
    {
        return 'base64:' . base64_encode(Str::random(32));
    }
}
