<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Pages;

use Mediamouse\Users\Filament\Resources\GroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * @property \Mediamouse\Users\Models\User record
 */
class ViewGroup extends ViewRecord
{
    protected static string $resource = GroupResource::class;
    protected static ?string $title = 'Group';

    protected function getTitle(): string
    {
        return "Group `{$this->record->name}`";
    }

    /**
     * @throws \Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function mountTableAction() {

    }
    public function edit() {

    }

    protected function getHeaderWidgets(): array
    {
        return [
        ];
    }

}

