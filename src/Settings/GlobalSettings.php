<?php

namespace Mediamouse\Users\Settings;

use Spatie\LaravelSettings\Settings;

class GlobalSettings extends Settings
{
//    password settings

    public string $date_format;
    public string $dateTime_format;
    public string $time_format;

    public string $number_format;

    public static function group(): string
    {
        return 'mediamouse-users-global';
    }
}
