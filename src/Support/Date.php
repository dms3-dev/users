<?php

namespace Mediamouse\Users\Support;

class Date {

    public static function format(): string
    {
        return self::dateTimeFormat();
    }

    public static function dateFormat(): string
    {
        return self::userDateFormat();
    }

    public static function timeFormat(): string
    {
        return self::userTimeFormat();
    }

    public static function dateTimeFormat(): string
    {
        return self::userDateTimeFormat();
    }

    public static function userFormat(): string
    {
        return 'M jS, Y H:i:s';
    }

    public static function userDateFormat(): string
    {
        return 'M jS, Y';
    }

    public static function userTimeFormat(): string
    {
        return 'H:i:s';
    }

    public static function userDateTimeFormat(): string
    {
        return 'M jS, Y H:i:s';
    }

    public static function globalFormat(): string
    {
        return 'M jS, Y H:i:s';
    }

    public static function globalTimeFormat(): string
    {
        return 'H:i:s';
    }

    public static function globalDateFormat(): string
    {
        return 'M jS, Y';
    }

    public static function globalDateTimeFormat(): string
    {
        return 'M jS, Y H:i:s';
    }
}
