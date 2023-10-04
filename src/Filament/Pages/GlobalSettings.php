<?php

namespace Mediamouse\Users\Filament\Pages;

use Carbon\Carbon;
use Filament\Facades\Filament;
use Illuminate\Contracts\Support\Htmlable;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Filament\Pages\Actions\ToggleMaintenanceAction;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings as GlobalSettingsModel;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Forms;

class GlobalSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $slug = 'global-settings';

    protected static string $settings = GlobalSettingsModel::class;

    public function __construct($id = null)
    {
        if(Filament::auth()->user()->role !== UserRole::SA) abort(403);
        parent::__construct($id);
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static function getNavigationLabel(): string
    {
        return __('mediamouse-users::pages/global-settings.menu-label');
    }

    protected function getTitle(): string
    {
        return __('mediamouse-users::pages/global-settings.title');
    }

    protected function getFormSchema(): array
    {

        return [
            Forms\Components\Grid::make(2)->schema([
                $this->fieldSetDateSettings(),
                $this->fieldsetCsvSettings(),
                $this->fieldSetNumberSettings(),
            ]),

        ];
    }


    private function fieldSetDateSettings(): Forms\Components\Fieldset {

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

        $date_options = [];
        $datetime_options = ['auto' => __('mediamouse-users::pages/global-settings.datetime-auto-setting')];
        $time_options = [];
        foreach($date_formats as $date_format) {
            $date_options[$date_format] = $date->format($date_format);
            foreach($time_formats as $time_format) {
                $datetime_format = $date_format . ' ' . $time_format;
                $datetime_options[$datetime_format] = $date->translatedFormat($datetime_format);
            }
        }

        foreach($time_formats as $time_format) {
            $time_options[$time_format] = $date->translatedFormat($time_format);
        }
        return Forms\Components\Fieldset::make(__('mediamouse-users::pages/global-settings.date-settings'))
            ->columnSpan(1)
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('date_format')
                    ->inlineLabel()
                    ->label(__('mediamouse-users::pages/global-settings.date-notation'))
                    ->options($date_options),
                Forms\Components\Select::make('time_format')
                    ->inlineLabel()
                    ->label(__('mediamouse-users::pages/global-settings.time-notation'))
                    ->options($time_options),
                Forms\Components\Select::make('dateTime_format')
                    ->inlineLabel()
                    ->label(__('mediamouse-users::pages/global-settings.datetime-notation'))
                    ->options($datetime_options),
            ]);
    }

    private function fieldSetNumberSettings(): Forms\Components\Fieldset {
        return
            Forms\Components\Fieldset::make(__('mediamouse-users::pages/global-settings.number-settings'))
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
                            'COMMA' => __('mediamouse-users::pages/user-settings.csv-delimiter-comma'),
                            'SEMICOLON' => __('mediamouse-users::pages/user-settings.csv-delimiter-semicolon'),
                            'TAB' => __('mediamouse-users::pages/user-settings.csv-delimiter-tab'),
                        ]),
                    Forms\Components\Select::make('csv_enclosure')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-enclosure'))
                        ->disablePlaceholderSelection()
                        ->options([
                            'single' => __('mediamouse-users::pages/user-settings.csv-enclosure-single'),
                            'double' => __('mediamouse-users::pages/user-settings.csv-enclosure-double'),
                        ]),
                    Forms\Components\Select::make('csv_new_line')
                        ->inlineLabel()
                        ->label(__('mediamouse-users::pages/user-settings.csv-new-line'))
                        ->disablePlaceholderSelection()
                        ->options([
                            'R' => __('mediamouse-users::pages/user-settings.csv-new-line-r'),
                            'N' => __('mediamouse-users::pages/user-settings.csv-new-line-n'),
                            'RN' => __('mediamouse-users::pages/user-settings.csv-new-line-rn'),
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
