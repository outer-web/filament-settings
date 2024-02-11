<?php

namespace Outerweb\FilamentSettings\Filament\Plugins;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentSettingsPlugin implements Plugin
{
    public array $pages = [];

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getPages(): array
    {
        return $this->pages;
    }

    public function pages(array $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getId(): string
    {
        return 'outerweb-filament-settings';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->pages($this->getPages());
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
