# Changelog

All notable changes to `filament-settings` will be documented in this file.

## 2.2.0 - 2026-01-08

### Added

-   Added support for relation fields in settings page.

## 2.1.0 - 2025-10-08

### Added

-   Use Gemini CLI to generate translations for languages supported by [Laravel Lang](https://github.com/Laravel-Lang)

## 2.0.0 - 2025-10-08

### Changed

-   This is a complete rewrite of the package that now has 100% test coverage. Follow the new installation instructions in the README to upgrade an existing installation.

## 1.3.0 - 2025-02-27

### Added

-   Added support for Laravel 12.

## 1.2.0 - 2024-03-25

### Changed

-   Set the static string `$view` instead of overriding the `getView` method in the base Settings page.

## 1.1.1 - 2024-03-12

### Added

-   Added support for Laravel 11.

## 1.1.0 - 2024-02-21

### Fixed

-   Filament fields that return arrays will now correctly be saved as one Setting entry instead of multiple. This is done so that editing the field in the settings panel will correctly update the field value in the database. (EG: Select with multiple values will now be saved as a single JSON array instead of multiple rows in the database. So that when you remove one of the values, it will correctly update the database.)

## 1.0.0 - 2024-02-11

-   Initial release
