<?php

namespace Outerweb\FilamentSettings\Commands;

use Illuminate\Console\Command;

class FilamentSettingsCommand extends Command
{
    public $signature = 'filament-settings';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
