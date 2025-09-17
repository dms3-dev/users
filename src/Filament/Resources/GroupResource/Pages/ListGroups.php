<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Pages;

use Filament\Facades\Filament;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Policies\GroupPolicy;

class ListGroups extends ListRecords
{
    protected static string $resource = GroupResource::class;

    public function getTitle(): string
    {
        return __('mediamouse-users::pages/group-resource.title');
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->visible(Filament::auth()->user()->hasPrivilege(GroupPolicy::class, PolicyPrivilege::CREATE))
                ->color('success')
                ->icon('heroicon-s-plus')
                ->after(function(Group $record, array $arguments) {

                    if(!isset($arguments['another']) || $arguments['another'] !== true) {
                        $this->redirect(GroupResource::getUrl('view', ['record' => $record]));
                    }
                }),
        ];
    }
}
