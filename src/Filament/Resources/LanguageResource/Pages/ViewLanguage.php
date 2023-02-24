<?php

namespace Mediamouse\Users\Filament\Resources\LanguageResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Mediamouse\Users\Filament\Resources\LanguageResource;

/**
 * @property \Mediamouse\Users\Models\Language record
 */
class ViewLanguage extends ViewRecord
{
    protected static string $resource = LanguageResource::class;
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

