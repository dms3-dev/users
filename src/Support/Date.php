<?php

namespace Mediamouse\Users\Support;

class Date {

    public static function format(): string
    {
        return self::userFormat();
    }

    public static function userFormat(): string
    {
        return 'Y-m-d H:i:s';
    }

    public static function globalFormat(): string
    {
        return 'Y-m-d H:i:s';
    }

}
