<?php

declare(strict_types=1);

use Livewire\Livewire;
use Outerweb\FilamentSettings\SettingsPlugin;
use Outerweb\FilamentSettings\Tests\Fixtures\Filament\Pages\Settings\Settings;
use Outerweb\FilamentSettings\Tests\TestCase;
use Outerweb\Settings\Facades\Setting;

it('can be added to a panel', function () {
    expect(filament()->hasPlugin(SettingsPlugin::get()->getId()))->toBeTrue();
});

it('renders the user defined settings pages correctly', function () {
    Livewire::test(Settings::class)
        ->assertOk()
        ->assertFormExists('form')
        ->assertFormFieldExists('general.brand_name')
        ->assertFormFieldExists('seo.title')
        ->assertFormFieldExists('seo.description');
});

it('renders the data correctly', function () {
    Setting::set([
        'general.brand_name' => 'My Brand',
        'seo.title' => 'My SEO Title',
        'seo.description' => 'My SEO Description',
    ]);

    Livewire::test(Settings::class)
        ->assertSchemaStateSet([
            'general.brand_name' => 'My Brand',
            'seo.title' => 'My SEO Title',
            'seo.description' => 'My SEO Description',
        ]);
});

it('saves the data correctly', function () {
    Livewire::test(Settings::class)
        ->fillForm([
            'general.brand_name' => 'My Brand',
            'seo.title' => 'My SEO Title',
            'seo.description' => 'My SEO Description',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    /** @var TestCase $this */
    $this->assertDatabaseHas('settings', [
        'key' => 'general.brand_name',
        'value' => '"My Brand"',
    ]);
    $this->assertDatabaseHas('settings', [
        'key' => 'seo.title',
        'value' => '"My SEO Title"',
    ]);
    $this->assertDatabaseHas('settings', [
        'key' => 'seo.description',
        'value' => '"My SEO Description"',
    ]);

    expect(Setting::get('general.brand_name'))->toBe('My Brand');
    expect(Setting::get('seo.title'))->toBe('My SEO Title');
    expect(Setting::get('seo.description'))->toBe('My SEO Description');
});

it('shows the success notification when saved', function () {
    Livewire::test(Settings::class)
        ->fillForm([
            'general.brand_name' => 'My Brand',
            'seo.title' => 'My SEO Title',
            'seo.description' => 'My SEO Description',
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();
});
