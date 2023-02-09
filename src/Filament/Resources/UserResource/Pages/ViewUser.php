<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Pages;

use Mediamouse\Users\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * @property \Mediamouse\Users\Models\User record
 */
class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
    protected static ?string $title = 'User';

    protected function getTitle(): string
    {
        return "User `{$this->record->name}`";
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
            UserResource\Widgets\UserOverview::class,
        ];
    }

}

