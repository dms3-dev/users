<?php

namespace Mediamouse\Users\Filament\Pages;

use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action;
use Filament\Pages\Contracts\HasFormActions;
use Filament\Pages\Page;
use Filament\Forms;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Models\User;

class UserSettings extends Page implements HasFormActions
{
    use \Filament\Pages\Concerns\HasFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $slug = 'user-settings';

    protected static string $view = 'filament-spatie-laravel-settings-plugin::pages.settings-page';

    public $data;

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? static::$title ?? __('mediamouse-users::pages/user-settings.title');
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return static::$title ?? __('mediamouse-users::pages/user-settings.title');
    }


    public function mount(): void
    {
        /** @var User $current_user */
        $current_user = Filament::auth()->user();

        $data = [
            'name' => $current_user->name,
            'email' => $current_user->email,
            'language_iso' => $current_user->language_iso,

            'date_format' => $current_user->getUserSetting('mediamouse-users.date_format'),
            'time_format' => $current_user->getUserSetting('mediamouse-users.time_format'),
            'dateTime_format' => $current_user->getUserSetting('mediamouse-users.dateTime_format'),
            'number_format' => $current_user->getUserSetting('mediamouse-users.number_format'),
            'csv_delimeter' => $current_user->getUserSetting('mediamouse-users.csv_delimiter'),
            'csv_enclosure' => $current_user->getUserSetting('mediamouse-users.csv_enclosure'),
            'csv_new_line' => $current_user->getUserSetting('mediamouse-users.csv_new_line'),
        ];

        $this->form->fill($data);
    }

    private function fieldsetLanguageSettings(): Forms\Components\Fieldset
    {
        return
            Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.language-settings'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Select::make('language_iso')
                        ->inlineLabel()
                        ->options(Language::query()->pluck('name', 'iso'))
                        ->label(__('mediamouse-users::pages/user-settings.language')),
                ]);
    }

    private function fieldsetDetailsSettings(): Forms\Components\Fieldset
    {
        return
            Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.user-details'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    TextInput::make('name')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.full-name')),
                    TextInput::make('email')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.email')),
                ]);
    }

    private function fieldsetNumberSettings(): Forms\Components\Fieldset
    {
        return
            Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.number-settings'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Forms\Components\Select::make('number_format')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.number-notation'))
                        ->disablePlaceholderSelection()
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'COMMA' => number_format(1234.56, 2, ',', ''),
                            'DOT' => number_format(1234.56, 2, '.', ''),
                            'COMMA_DOT' => number_format(1234.56, 2, ',', '.'),
                            'DOT_COMMA' => number_format(1234.56, 2, '.', ','),
                        ]),
                ]);
    }

    private function fieldsetCsvSettings(): Forms\Components\Fieldset
    {
        return
            Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.csv-settings'))
                ->columnSpan(1)
                ->columns(1)
                ->schema([
                    Forms\Components\Select::make('csv_delimiter')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-delimiter'))
                        ->disablePlaceholderSelection()
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'COMMA' => __('mediamouse-users::pages/user-settings.csv-delimiter-comma'),
                            'SEMICOLON' => __('mediamouse-users::pages/user-settings.csv-delimiter-semicolon'),
                            'TAB' => __('mediamouse-users::pages/user-settings.csv-delimiter-tab'),
                        ]),
                    Forms\Components\Select::make('csv_enclosure')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-enclosure'))
                        ->disablePlaceholderSelection()
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'single' => __('mediamouse-users::pages/user-settings.csv-enclosure-single'),
                            'double' => __('mediamouse-users::pages/user-settings.csv-enclosure-double'),
                        ]),
                    Forms\Components\Select::make('csv_new_line')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-new-line'))
                        ->disablePlaceholderSelection()
                        ->options([
                            'global' => __('mediamouse-users::pages/user-settings.datetime-global-setting'),
                            'R' => __('mediamouse-users::pages/user-settings.csv-new-line-r'),
                            'N' => __('mediamouse-users::pages/user-settings.csv-new-line-n'),
                            'RN' => __('mediamouse-users::pages/user-settings.csv-new-line-rn'),
                        ]),
                ]);
    }

    private function fieldsetDateSettings(): Forms\Components\Fieldset
    {
        $date = Carbon::create(2023, 8, 1, 4, 35, 59);

        $date_formats = [
            'M jS, Y',
            'j M Y',
            'j F Y',
            'D j M Y',
            'D M jS, Y',
            'l j F Y',
            'j-n-Y',
            'd-m-Y',
            'm-d-Y',
            'n-j-Y',
            'Y-m-d',
        ];

        $time_formats = [
            'H:i:s',
            'h:i:s a',
            'h:i:s A',
            'G:i:s',
            'g:i:s a',
            'g:i:s A',
        ];

        $date_options = ['global' => __('mediamouse-users::pages/user-settings.datetime-global-setting')];
        $datetime_options = ['auto' => __('mediamouse-users::pages/user-settings.datetime-auto-setting')];
        $time_options = ['global' => __('mediamouse-users::pages/user-settings.datetime-global-setting')];
        foreach($date_formats as $date_format) {
            $date_options[$date_format] = $date->translatedFormat($date_format);
            foreach($time_formats as $time_format) {
                $datetime_format = $date_format . ' ' . $time_format;
                $datetime_options[$datetime_format] = $date->translatedFormat($datetime_format);
            }
        }

        foreach($time_formats as $time_format) {
            $time_options[$time_format] = $date->translatedFormat($time_format);
        }

        return
                Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-settings.date-settings'))
                    ->columnSpan(1)
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('date_format')
                            ->inlineLabel()
                            ->disablePlaceholderSelection()
                            ->label(__('mediamouse-users::pages/user-settings.date-notation'))
                            ->options($date_options),
                        Forms\Components\Select::make('time_format')
                            ->inlineLabel()
                            ->disablePlaceholderSelection()
                            ->label(__('mediamouse-users::pages/user-settings.time-notation'))
                            ->options($time_options),
                        Forms\Components\Select::make('dateTime_format')
                            ->inlineLabel()
                            ->disablePlaceholderSelection()
                            ->label(__('mediamouse-users::pages/user-settings.datetime-notation'))
                            ->options($datetime_options),
                ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        $this->fieldsetDetailsSettings(),
                        $this->fieldsetLanguageSettings(),
                        $this->fieldsetNumberSettings(),
                    ]),
                Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        $this->fieldsetDateSettings(),
                        $this->fieldsetCsvSettings(),
                    ]),
                ])
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        /** @var User $current_user */
        $current_user = Filament::auth()->user();

        $refreshPage = $current_user->language_iso != $data['language_iso'];

        $current_user->name = $data['name'];
        $current_user->email = $data['email'];
        $current_user->language_iso = $data['language_iso'];

        $current_user->save();

        $current_user->updateUserSetting('mediamouse-users.date_format', $data['date_format']);
        $current_user->updateUserSetting('mediamouse-users.time_format', $data['time_format']);
        $current_user->updateUserSetting('mediamouse-users.dateTime_format', $data['dateTime_format']);
        $current_user->updateUserSetting('mediamouse-users.number_format', $data['number_format']);
        $current_user->updateUserSetting('mediamouse-users.csv_delimiter', $data['csv_delimiter']);
        $current_user->updateUserSetting('mediamouse-users.csv_enclosure', $data['csv_enclosure']);
        $current_user->updateUserSetting('mediamouse-users.csv_new_line', $data['csv_new_line']);

        Notification::make()
            ->success()
            ->title(__('mediamouse-users::pages/user-settings.user-updated'))
            ->send();

        if($refreshPage) {
            response()->redirectTo(route('filament.pages.user-settings'));
        }
    }


    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-spatie-laravel-settings-plugin::pages/settings-page.form.actions.save.label'))
            ->submit('submit')
            ->keyBindings(['mod+s']);
    }

}

