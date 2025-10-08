<?php

namespace Outerweb\FilamentSettings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Outerweb\FilamentSettings\FilamentSettings
 */
class FilamentSettings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Outerweb\FilamentSettings\FilamentSettings::class;
    }
}
