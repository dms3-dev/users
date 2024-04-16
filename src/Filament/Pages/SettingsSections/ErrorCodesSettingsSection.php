<?php

namespace Mediamouse\Users\Filament\Pages\SettingsSections;

use Filament\Forms\Components\Section;
use Filament\Forms;
use Mediamouse\Users\Settings\GlobalSettings;

class ErrorCodesSettingsSection extends AbstractSettingsSection
{
    protected static string $settings = GlobalSettings::class;

    public static function section(): Section
    {
        return Forms\Components\Section::make(__('mediamouse-users::pages/global-settings.error-codes-settings'))
            ->columnSpan(1)
            ->columns(1)
            ->schema([
                Forms\Components\RichEditor::make('internal_error_text')
                    ->inlineLabel(),
                Forms\Components\RichEditor::make('forbidden_text')
                    ->inlineLabel(),
                Forms\Components\RichEditor::make('$page_not_found_text')
                    ->inlineLabel(),
            ]);
    }


}
