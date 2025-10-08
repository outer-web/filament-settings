<?php

declare(strict_types=1);

namespace Outerweb\FilamentSettings\Tests\Fixtures\Filament\Pages\Settings;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Outerweb\FilamentSettings\Pages\Settings as BaseSettings;

class Settings extends BaseSettings
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('General')
                            ->schema([
                                TextInput::make('general.brand_name')
                                    ->required(),
                            ]),
                        Tab::make('Seo')
                            ->schema([
                                TextInput::make('seo.title')
                                    ->required(),
                                TextInput::make('seo.description')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
