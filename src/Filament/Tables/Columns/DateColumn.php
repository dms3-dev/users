<?php

namespace Mediamouse\Users\Filament\Tables\Columns;

use Filament\Facades\Filament;
use Filament\Tables\Columns\TextColumn;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings;
use Mediamouse\Users\Support\Date;

class DateColumn extends TextColumn
{

    public static function make(?string $name = null): static
    {
        return parent::make($name)
            ->dateTime(Date::userDateFormat());
    }
}
