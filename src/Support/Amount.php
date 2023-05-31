<?php

namespace Mediamouse\Users\Support;

class Amount {

    public static function format(float $amount): string
    {
        return self::userFormat($amount);
    }

    public static function userFormat(float $amount): string
    {
        return number_format($amount, 2, ',', '');
    }
    
    public static function globalFormat(float $amount): string
    {
        return number_format($amount, 2, ',', '');
    }

}
