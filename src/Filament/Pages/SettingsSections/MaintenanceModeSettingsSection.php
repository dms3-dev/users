<?php

namespace Mediamouse\Users\Filament\Pages\SettingsSections;

use Filament\Forms\Components\Section;
use Filament\Forms;
use Mediamouse\Users\Settings\GlobalSettings;

class MaintenanceModeSettingsSection extends AbstractSettingsSection
{
    protected static string $settings = GlobalSettings::class;

    public static function section(): Section
    {
        return Forms\Components\Section::make(__('mediamouse-users::pages/global-settings.maintenance-settings'))
            ->columnSpan(1)
            ->columns(1)
            ->schema([
                Forms\Components\RichEditor::make('maintenance_text')
                    ->inlineLabel(),
            ]);
    }


}
