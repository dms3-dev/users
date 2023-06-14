<?php

namespace Mediamouse\Users\Support;

use Filament\Facades\Filament;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings;

class Csv {


    public static function separator() {
        /** @var User $current_user */
        $current_user = Filament::auth()->user();
        $setting = $current_user->getUserSetting('mediamouse-users.csv_delimiter');

        if($setting === 'global') $setting = app(GlobalSettings::class)->csv_delimiter;

        return match($setting) {
            'COMMA' => ",",
            'SEMICOLON' => ";",
            'TAB' => "\t",
            default => ','
        };

    }

    public static function newline() {
        /** @var User $current_user */
        $current_user = Filament::auth()->user();
        $setting = $current_user->getUserSetting('mediamouse-users.csv_new_line');

        if($setting === 'global') $setting = app(GlobalSettings::class)->csv_new_line;

        return match($setting) {
            'R' => "\r",
            'N' => "\n",
            'RN' => "\r\n",
            default => "\r\n"
        };

    }

    public static function enclosure() {
        /** @var User $current_user */
        $current_user = Filament::auth()->user();
        $setting = $current_user->getUserSetting('mediamouse-users.csv_enclosure');

        if($setting === 'global') $setting = app(GlobalSettings::class)->csv_enclosure;

        return match($setting) {
            'SINGLE' => "'",
            'DOUBLE' => '"',
            default => '"'
        };
    }

}
