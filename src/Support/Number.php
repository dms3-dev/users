<?php

namespace Mediamouse\Users\Support;

use Filament\Facades\Filament;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings;

class Number {

    public static function format(float $amount, int $decimals = 2): string
    {
        return self::userFormat($amount, $decimals);
    }

    public static function userFormat(float $amount, int $decimals = 2): string
    {
        /** @var User $current_user */
        $current_user = Filament::auth()->user();
        $format = $current_user->getUserSetting('mediamouse-users.number_format');

        if($format === null || $format === 'global') {
            return self::globalFormat($amount, $decimals);
        }

        return self::formatNumber($amount, $format, $decimals);
    }

    public static function globalFormat(float $amount, int $decimals = 2): string
    {
        return self::formatNumber($amount, app(GlobalSettings::class)->number_format, $decimals);
    }

    protected static function formatNumber(float $amount, string $format, int $decimals = 2) : string {
        return match($format) {
            'COMMA' => number_format($amount, $decimals, ',', ''),
            'DOT' => number_format($amount, $decimals, '.', ''),
            'COMMA_DOT' => number_format($amount, $decimals, ',', '.'),
            'DOT_COMMA' => number_format($amount, $decimals, '.', ','),
            default => $format
        };
    }

}
