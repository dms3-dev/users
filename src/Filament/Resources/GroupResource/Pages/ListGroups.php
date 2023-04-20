<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Pages;

use Mediamouse\Users\Filament\Resources\GroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->color('success')
                ->icon('heroicon-s-plus'),
        ];
    }
}
