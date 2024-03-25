<?php

namespace Outerweb\FilamentSettings\Filament\Pages;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Field;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Str;
use Outerweb\Settings\Models\Setting;

/**
 * @property Form $form
 */
class Settings extends Page
{
    use HasUnsavedDataChangesAlert;
    use InteractsWithFormActions;

    public ?array $data = [];

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament-settings::filament/pages/settings';

    public static function getNavigationLabel() : string
    {
        return __('filament-settings::translations.page.navigation_label');
    }

    public function getLayout() : string
    {
        return static::$layout ?? 'filament-panels::components.layout.index';
    }

    public function getTitle() : string
    {
        return __('filament-settings::translations.page.title');
    }

    public function schema() : array|Closure
    {
        return [];
    }

    public function form(Form $form) : Form
    {
        return $form
            ->schema($this->schema())
            ->statePath('data');
    }

    public function getFormActions() : array
    {
        return [
            Action::make('save')
                ->label(__('filament-settings::translations.form.actions.save'))
                ->submit('data')
                ->keyBindings(['mod+s'])
        ];
    }

    public function mount() : void
    {
        $this->fillForm();
    }

    protected function fillForm() : void
    {
        $data = Setting::get();

        $this->callHook('beforeFill');

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    public function save() : void
    {
        try {
            $this->callHook('beforeValidate');

            $fields = collect($this->form->getFlatFields(true));
            $fieldsWithNestedFields = $fields->filter(fn (Field $field) => count($field->getChildComponents()) > 0);

            $fieldsWithNestedFields->each(function (Field $fieldWithNestedFields, string $fieldWithNestedFieldsKey) use (&$fields) {
                $fields = $fields->reject(function (Field $field, string $fieldKey) use ($fieldWithNestedFields, $fieldWithNestedFieldsKey) {
                    return Str::startsWith($fieldKey, $fieldWithNestedFieldsKey . '.');
                });
            });

            $data = $fields->mapWithKeys(function (Field $field, string $fieldKey) {
                return [$fieldKey => data_get($this->form->getState(), $fieldKey)];
            })->toArray();

            $this->callHook('afterValidate');

            $this->callHook('beforeSave');

            foreach ($data as $key => $value) {
                Setting::set($key, $value);
            }

            $this->callHook('afterSave');
        } catch (Halt $exception) {
            return;
        }

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }

    protected function getSavedNotification() : ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($this->getSavedNotificationTitle());
    }

    protected function getSavedNotificationTitle() : ?string
    {
        return __('filament-settings::translations.notifications.saved');
    }

    protected function getRedirectUrl() : ?string
    {
        return null;
    }
}
