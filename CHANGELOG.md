# Changelog

All notable changes to `filament-settings` will be documented in this file.

## 1.2.0 - 2024-03-25

### Changed

- Set the static string `$view` instead of overriding the `getView` method in the base Settings page.

## 1.1.1 - 2024-03-12

### Added

- Added support for Laravel 11.

## 1.1.0 - 2024-02-21

### Fixed

- Filament fields that return arrays will now correctly be saved as one Setting entry instead of multiple. This is done so that editing the field in the settings panel will correctly update the field value in the database. (EG: Select with multiple values will now be saved as a single JSON array instead of multiple rows in the database. So that when you remove one of the values, it will correctly update the database.)

## 1.0.0 - 2024-02-11

- Initial release
