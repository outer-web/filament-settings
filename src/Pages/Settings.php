<?php

declare(strict_types=1);

namespace Outerweb\FilamentSettings\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Field;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Outerweb\Settings\Facades\Setting;
use Outerweb\Settings\Models\Setting as SettingModel;
use Throwable;

/**
 * @property-read Schema $form
 */
class Settings extends Page
{
    use CanUseDatabaseTransactions;
    use HasUnsavedDataChangesAlert;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog8Tooth;

    public function getTitle(): string|Htmlable
    {
        return __('filament-settings::settings.page.title');
    }

    public function mount(): void
    {
        $this->form->components(
            collect($this->form->getComponents(true, true))
                ->map(function (Component|Action|ActionGroup $component): Component|Action|ActionGroup {
                    return $this->addModelToFieldComponentsRecursively($component);
                })
                ->all(),
        );

        $this->fillForm();
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            Setting::set($data);

            foreach ($this->form->getFlatComponents() as $component) {
                if (! $component instanceof Field) {
                    continue;
                }

                $component->model($this->getModelForField($component));
                $component->saveRelationships();
            }

            $this->callHook('afterSave');
            // @codeCoverageIgnoreStart
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollBackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }
        // @codeCoverageIgnoreEnd

        $this->commitDatabaseTransaction();

        $this->rememberData();

        $this->getSavedNotification()?->send();

        // @codeCoverageIgnoreStart
        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode($redirectUrl));
        }
        // @codeCoverageIgnoreEnd
    }

    public function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        // @codeCoverageIgnoreStart
        if (blank($title)) {
            return null;
        }
        // @codeCoverageIgnoreEnd

        return Notification::make()
            ->success()
            ->title($title);
    }

    public function getSavedNotificationTitle(): ?string
    {
        return __('filament-settings::settings.notifications.saved.title');
    }

    /**
     * @return array<Action|ActionGroup>
     */
    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        $hasFormWrapper = $this->hasFormWrapper();

        return Action::make('save')
            ->label(__('filament-settings::settings.form.actions.save.label'))
            ->submit($hasFormWrapper ? $this->getSubmitFormLivewireMethodName() : null)
            ->action($hasFormWrapper ? null : $this->getSubmitFormLivewireMethodName())
            ->keyBindings(['mod+s']);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->inlineLabel($this->hasInlineLabels())
            ->statePath('data');
    }

    // @codeCoverageIgnoreStart
    public function form(Schema $schema): Schema
    {
        return $schema;
    }
    // @codeCoverageIgnoreEnd

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        // @codeCoverageIgnoreStart
        if (! $this->hasFormWrapper()) {
            return Group::make([
                EmbeddedSchema::make('form'),
                $this->getFormActionsContentComponent(),
            ]);
        }
        // @codeCoverageIgnoreEnd

        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler($this->getSubmitFormLivewireMethodName())
            ->footer([
                $this->getFormActionsContentComponent(),
            ]);
    }

    public function getFormActionsContentComponent(): Component
    {
        return Actions::make($this->getFormActions())
            ->alignment($this->getFormActionsAlignment())
            ->fullWidth($this->hasFullWidthFormActions())
            ->sticky($this->areFormActionsSticky())
            ->key('form-actions');
    }

    public function hasFormWrapper(): bool
    {
        return true;
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $this->data = $this->mutateFormDataBeforeFill(Setting::get() ?? []);

        $this->form->fill($this->data);

        $this->callHook('afterFill');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    protected function getSubmitFormLivewireMethodName(): string
    {
        return 'save';
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    private function addModelToFieldComponentsRecursively(Component|Action|ActionGroup $component): Component|Action|ActionGroup
    {
        if ($component instanceof Field) {
            $component->model(function (Field $component): SettingModel {
                return $this->getModelForField($component);
            });
        }

        return $component->childComponents(
            collect($component->getChildComponents())
                ->map(function (Component|Action|ActionGroup $childComponent): Action|ActionGroup|Component {
                    return $this->addModelToFieldComponentsRecursively($childComponent);
                })
                ->all(),
        );
    }

    private function getModelForField(Field $field): SettingModel
    {
        return Setting::model()::query()
            ->firstOrNew(['key' => $field->getName()]);
    }
}
