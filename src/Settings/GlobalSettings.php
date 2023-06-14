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

    public string $csv_delimiter;
    public string $csv_enclosure;
    public string $csv_new_line;

    public static function group(): string
    {
        return 'mediamouse-users-global';
    }
}
