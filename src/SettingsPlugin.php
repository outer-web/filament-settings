<?php

declare(strict_types=1);

namespace Outerweb\FilamentSettings;

use Filament\Contracts\Plugin;
use Filament\Panel;

class SettingsPlugin implements Plugin
{
    protected array $pages = [];

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
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

    // @codeCoverageIgnoreStart
    public function boot(Panel $panel): void {}
    // @codeCoverageIgnoreEnd

    public function pages(array $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getPages(): array
    {
        return $this->pages;
    }
}
