<?php

namespace Mediamouse\Users\Filament\Pages;

use Carbon\Carbon;
use Filament\Actions\Concerns\HasForm;
use Filament\Facades\Filament;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Str;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Filament\Pages\Actions\ToggleMaintenanceAction;
use Mediamouse\Users\Filament\Pages\SettingsSections\Sections;
use Mediamouse\Users\Filament\Pages\SettingsSections\CsvExportSettingsSection;
use Mediamouse\Users\Filament\Pages\SettingsSections\DateSettingsSection;
use Mediamouse\Users\Filament\Pages\SettingsSections\NumberSettingsSection;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings as GlobalSettingsModel;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Forms;
use Filament\Forms\ComponentContainer;

class GlobalSettings extends Page implements Forms\Contracts\HasForms
{
    use InteractsWithForms;
    use InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $slug = 'global-settings';


    protected static string $view = 'filament-spatie-laravel-settings-plugin::pages.settings-page';

    public $data;

    public function __construct($id = null)
    {
        if(auth()->user()->role !== UserRole::SA) abort(403);
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {

       $data = [];
        foreach (Sections::$sections as $section){
            $data += array_merge(
                $section::data());
        }

        $this->form->fill($data);
    }

    protected function getFormSchema(): array
    {

        return Sections::getSections();

    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (Sections::$sections as $section){
            $section::save($data);
        }


        Notification::make()
            ->success()
            ->title('Saved')->send();
    }


    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-spatie-laravel-settings-plugin::pages/settings-page.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data')
                ->columns(2)
                ->inlineLabel(config('filament.layout.forms.have_inline_labels')),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getNavigationLabel(): string
    {
        return __('mediamouse-users::pages/global-settings.menu-label');
    }

    public function getTitle(): string
    {
        return __('mediamouse-users::pages/global-settings.title');
    }




    private function fieldSetNumberSettings(): Forms\Components\Section {
        return
            Forms\Components\Section::make(__('mediamouse-users::pages/global-settings.number-settings'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Forms\Components\Select::make('number_format')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/global-settings.number-notation'))
                        ->options([
                            'COMMA' => number_format(1234.56, 2, ',', ''),
                            'DOT' => number_format(1234.56, 2, '.', ''),
                            'COMMA_DOT' => number_format(1234.56, 2, ',', '.'),
                            'DOT_COMMA' => number_format(1234.56, 2, '.', ','),
                        ]),
                ]);
    }

    protected function getActions(): array
    {
        return [
            ToggleMaintenanceAction::make()
            ->color('danger')
        ];
    }
}
