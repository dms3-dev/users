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
            Card::make((__('mediamouse-users::model/user-model.username')), $this->record->username)
                        ->description((__('mediamouse-users::model/user-model.full_name')) . ': ' . $this->record->name),
            Card::make((__('mediamouse-users::model/user-model.email_address')), $this->record->email),
            Card::make((__('mediamouse-users::model/user-model.role')), $this->record->role),
        ];
    }
}
