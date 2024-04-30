<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Pages;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    public function getTitle(): string
    {
        return __('mediamouse-users::pages/user-resource.title');
    }
    protected function getActions(): array
    {
        return [
           UserResource\Actions\CreateUserAction::make()
                ->color('success')
                ->icon('heroicon-s-plus'),
        ];
    }

    protected function getTableQuery(): Builder
    {
        if(Filament::auth()->user()->role == UserRole::SA) {
            return parent::getTableQuery()
                ->whereIn('role', [UserRole::SA, UserRole::ADMINISTRATOR]);

        }
        return parent::getTableQuery()
                ->whereIn('role', [UserRole::ADMINISTRATOR]);
    }
}
