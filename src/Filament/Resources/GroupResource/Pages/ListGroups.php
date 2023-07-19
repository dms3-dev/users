<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Pages;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Mediamouse\Users\Policies\GroupPolicy;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupResource::class;

    protected function getTitle(): string
    {
        return __('mediamouse-users::pages/group-resource.title');
    }

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->visible(Filament::auth()->user()->hasPrivilege(GroupPolicy::class, PolicyPrivilege::CREATE))
                ->color('success')
                ->icon('heroicon-s-plus'),
        ];
    }
}
