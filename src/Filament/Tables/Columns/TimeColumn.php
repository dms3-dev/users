<?php

namespace Mediamouse\Users\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Mediamouse\Users\Support\Date;

class TimeColumn extends TextColumn
{

    public static function make(string $name): static
    {
        return parent::make($name)
            ->dateTime(Date::userTimeFormat());
    }

}
