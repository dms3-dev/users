<?php

namespace Mediamouse\Users\Support;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Filament\Facades\Filament;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings;

class Date {

    public static function dateTime() {
        return fn($state) => $state->format(Date::userDateTimeFormat());
    }

    public static function date() {
        return fn($state) => $state->format(Date::userDateFormat());
    }

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
        if(Filament::auth()->user()) {
            /** @var User $current_user */
            $current_user = Filament::auth()->user();

            $format = $current_user->getUserSetting('mediamouse-users.date_format');

            if($format !== null && $format !== 'global') {
                return $format;
            }
        }
        return self::globalDateFormat();
    }

    public static function userTimeFormat(): string
    {
        if(Filament::auth()->user()) {
            /** @var User $current_user */
            $current_user = Filament::auth()->user();

            $format = $current_user->getUserSetting('mediamouse-users.time_format');

            if($format !== null && $format !== 'global') {
                return $format;
            }
        }
        return self::globalTimeFormat();
    }

    public static function userDateTimeFormatWithoutSeconds(): string {
        return substr(self::userDateTimeFormat(), 0, -2);
    }

    public static function userDateTimeFormat($bugsnag = true): string
    {
        if(Filament::auth()->user()) {
            /** @var User $current_user */
            $current_user = Filament::auth()->user();

            $format = $current_user->getUserSetting('mediamouse-users.dateTime_format');

            if($format !== null && $format !== 'global' && $format !== 'auto' && $format !== 'split') {
                return $format;
            }
            if($format === 'split') {
                if($bugsnag) Bugsnag::notifyError('DateTimeSplitError', 'Date Time (user) not properly splitted');
                return self::userDateFormat() . ' ' . self::userTimeFormat();
            }
            if($format === 'auto' || $format === 'global') {
                return self::userDateFormat() . ' ' . self::userTimeFormat();
            }
        }
        return self::globalDateTimeFormat($bugsnag);
    }

    public static function globalFormat(): string
    {
        return self::globalDateTimeFormat();
    }

    public static function globalTimeFormat(): string
    {
        return app(GlobalSettings::class)->time_format;
    }

    public static function globalDateFormat(): string
    {
        return app(GlobalSettings::class)->date_format;
    }

    public static function globalDateTimeFormat($bugsnag = true): string
    {
        $format = app(GlobalSettings::class)->dateTime_format;

        if($format === 'split' && $bugsnag) {
            if($bugsnag) Bugsnag::notifyError('DateTimeSplitError', 'Date Time (global) not properly splitted');
            $format = self::globalDateFormat() . ' ' . self::globalTimeFormat();
        }
        else if($format == 'auto' || $format === 'split') {
            $format = self::globalDateFormat() . ' ' . self::globalTimeFormat();
        }

        return $format;
    }
}
