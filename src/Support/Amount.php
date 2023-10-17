<?php

namespace Mediamouse\Users\Support;

use Filament\Facades\Filament;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings;

class Amount extends Number {

    public static function format(float $amount, int $decimals = 2): string
    {
        return parent::format($amount, $decimals);
    }

    public static function userFormat(float $amount, int $decimals = 2): string
    {
        return parent::userFormat($amount, $decimals);
    }

    public static function globalFormat(float $amount, int $decimals = 2): string
    {
        return parent::globalFormat($amount, $decimals);
    }

}
