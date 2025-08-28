<?php

namespace Ihasan\LaravelMailerlite\Commands;

use Illuminate\Console\Command;

class LaravelMailerliteCommand extends Command
{
    public $signature = 'laravel-mailerlite';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
