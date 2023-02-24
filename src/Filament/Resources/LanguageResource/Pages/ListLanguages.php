<?php

namespace Mediamouse\Users\Filament\Resources\LanguageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Mediamouse\Users\Filament\Resources\LanguageResource;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
