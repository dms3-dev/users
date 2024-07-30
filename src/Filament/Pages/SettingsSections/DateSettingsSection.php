<?php

namespace Mediamouse\Users\Filament\Pages\SettingsSections;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Mediamouse\Users\Settings\GlobalSettings;

class DateSettingsSection extends AbstractSettingsSection
{
    protected static string $settings = GlobalSettings::class;

    public static function section(): Section
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

        $date_options = [];
        $datetime_options = [
                'auto' => __('mediamouse-users::pages/global-settings.datetime-auto-setting'),
                'split' => __('mediamouse-users::pages/global-settings.datetime-split-setting'),
            ];
        $time_options = [];
        foreach ($date_formats as $date_format) {
            $date_options[$date_format] = $date->format($date_format);
            foreach ($time_formats as $time_format) {
                $datetime_format = $date_format . ' ' . $time_format;
                $datetime_options[$datetime_format] = $date->translatedFormat($datetime_format);
            }
        }

        foreach ($time_formats as $time_format) {
            $time_options[$time_format] = $date->translatedFormat($time_format);
        }
        return Forms\Components\Section::make(__('mediamouse-users::pages/global-settings.date-settings'))
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

}
