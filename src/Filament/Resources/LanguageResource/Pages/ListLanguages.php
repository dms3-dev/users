<?php

namespace Mediamouse\Users\Filament\Resources\LanguageResource\Pages;

use Exception;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Mediamouse\Users\Filament\Resources\LanguageResource;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguageResource::class;

    /**
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
