<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Pages;

use Mediamouse\Users\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * @return mixed
     */
    public function getRedirectUrl() :string
    {
        return UserResource::getUrl('view', ['record' => $this->record]);
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-s-trash'),
        ];
    }
}
