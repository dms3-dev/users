<?php

namespace Mediamouse\Users\Support;

use Filament\Facades\Filament;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings;

class Amount {

    public static function format(float $amount): string
    {
        return self::userFormat($amount);
    }

    public static function userFormat(float $amount): string
    {
        /** @var User $current_user */
        $current_user = Filament::auth()->user();
        $format = $current_user->getUserSetting('mediamouse-users.number_format');

        if($format === null || $format === 'global') {
            return self::globalFormat($amount);
        }

        return self::formatNumber($amount, $format);
    }

    public static function globalFormat(float $amount): string
    {
        return self::formatNumber($amount, app(GlobalSettings::class)->number_format);
    }

    private static function formatNumber(float $amount, string $format) {
        return match($format) {
            'COMMA' => number_format($amount, 2, ',', ''),
            'DOT' => number_format($amount, 2, '.', ''),
            'COMMA_DOT' => number_format($amount, 2, ',', '.'),
            'DOT_COMMA' => number_format($amount, 2, '.', ','),
            default => $format
        };
    }

}
