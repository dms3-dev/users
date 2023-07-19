<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Pages;

use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGroup extends EditRecord
{
    protected static string $resource = GroupResource::class;

    public function getRedirectUrl() :string
    {
        return GroupResource::getUrl('view', ['record' => $this->record]);
    }

    protected function getActions(): array
    {
        return [
//            Actions\DeleteAction::make(),
        ];
    }
}
