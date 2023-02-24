<?php

namespace Mediamouse\Users\Filament\Resources\LanguageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Mediamouse\Users\Filament\Resources\LanguageResource;

class EditLanguage extends EditRecord
{
    protected static string $resource = LanguageResource::class;

    public function getRedirectUrl() :string
    {
        return LanguageResource::getUrl('view', ['record' => $this->record]);
    }

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
