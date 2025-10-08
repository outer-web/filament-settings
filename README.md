# Filament integration for the outerweb/settings package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/outerweb/filament-settings.svg?style=flat-square)](https://packagist.org/packages/outerweb/filament-settings)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/outerweb/filament-settings/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/outerweb/filament-settings/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/outerweb/filament-settings/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/outerweb/filament-settings/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/outerweb/filament-settings.svg?style=flat-square)](https://packagist.org/packages/outerweb/filament-settings)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require outerweb/filament-settings
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-settings-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-settings-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-settings-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentSettings = new Outerweb\FilamentSettings();
echo $filamentSettings->echoPhrase('Hello, Outerweb!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Outerweb](https://github.com/outer-web)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
