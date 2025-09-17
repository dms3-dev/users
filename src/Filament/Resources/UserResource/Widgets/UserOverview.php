<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Mediamouse\Users\Models\User;

class UserOverview extends \Filament\Widgets\StatsOverviewWidget
{
    public ?User $record;

    protected function getCards(): array
    {
        return [
            Stat::make((__('mediamouse-users::model/user-model.username')), $this->record->username)
                        ->description((__('mediamouse-users::model/user-model.full_name')) . ': ' . $this->record->name),
            Stat::make((__('mediamouse-users::model/user-model.email_address')), $this->record->email),
            Stat::make((__('mediamouse-users::model/user-model.role')), $this->record->role),
        ];
    }
}
