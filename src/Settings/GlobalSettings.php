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

    public string $maintenance_text;
    public string $internal_error_text;
    public string $forbidden_text;
    public string $page_not_found_text;

    public static function group(): string
    {
        return 'mediamouse-users-global';
    }
}
