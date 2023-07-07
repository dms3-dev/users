<?php

namespace Mediamouse\Users\Filament\Resources\LanguageResource\Pages;

use Exception;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Mediamouse\Users\Filament\Resources\LanguageResource;
use Filament\Resources\Pages\ManageRecords;

class ListLanguages extends ManageRecords
{
    protected static string $resource = LanguageResource::class;

    /**
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label((__('mediamouse-users::pages/language-resource.create_language')))
                ->color('success')
                ->icon('heroicon-s-plus'),
        ];
    }
}
