<?php

namespace Mediamouse\Users\Filament\Tables\Columns;

use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Mediamouse\Users\Settings\GlobalSettings;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Support\Date;
use Carbon\Carbon;

class DateTimeColumn extends TextColumn
{

    public static function make(?string $name = null): static
    {
        /** @var User $current_user */
        $current_user = Filament::auth()->user();

        $global_format = app(GlobalSettings::class)->dateTime_format;
        $format = $current_user->getUserSetting('mediamouse-users.dateTime_format') ??
            $global_format;

        if($format === 'split' || ($format === 'auto' && $global_format === 'split')) {
            return parent::make($name)
                ->dateTime(Date::userDateFormat())
                ->tooltip(fn($record) => Carbon::make($record?->$name)?->format(Date::userDateTimeFormat(false)));

        }
        return parent::make($name)
                ->dateTime(Date::userDateTimeFormat(false));
    }

}
