<?php

declare(strict_types=1);

it('can be instantiated', function () {
    $plugin = Outerweb\FilamentSettings\SettingsPlugin::make();

    expect($plugin)
        ->toBeInstanceOf(Outerweb\FilamentSettings\SettingsPlugin::class);
});
