<?php

namespace Mediamouse\Users\Filament\Pages\SettingsSections;

use Filament\Schemas\Components\Section;
use Filament\Forms;
use Mediamouse\Users\Settings\GlobalSettings;

class CsvExportSettingsSection extends AbstractSettingsSection
{
    protected static string $settings = GlobalSettings::class;

    public static function section(): Section
    {
        return Section::make(__('mediamouse-users::pages/user-settings.csv-settings'))
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


}
