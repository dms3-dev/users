<?php

namespace Mediamouse\Users\Filament\Pages\SettingsSections;

use Filament\Forms\Components\Section;
use Filament\Forms;
use Mediamouse\Users\Settings\GlobalSettings;

class NumberSettingsSection extends AbstractSettingsSection
{
    protected static string $settings = GlobalSettings::class;

    public static function section(): Section
    {
        return Forms\Components\Section::make(__('mediamouse-users::pages/global-settings.number-settings'))
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


}
