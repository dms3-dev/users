<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Mediamouse\Users\Models\User;

class UserOverview extends BaseWidget
{
    public ?User $record;

    protected function getCards(): array
    {
        return [
            Card::make('Username', $this->record->username)
                        ->description("Full name: {$this->record->name}"),
            Card::make('E-mail address', $this->record->email),
            Card::make('Role: ', $this->record->role),
        ];
    }
}
