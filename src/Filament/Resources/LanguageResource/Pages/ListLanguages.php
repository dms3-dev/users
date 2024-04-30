<?php

namespace Mediamouse\Users\Filament\Resources\LanguageResource\Pages;

use Exception;
use Filament\Actions\CreateAction;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Mediamouse\Users\Filament\Resources\LanguageResource;

class ListLanguages extends ManageRecords
{
    protected static string $resource = LanguageResource::class;

    public function getTableRecordTitle(Model $record): string
    {
        return __('mediamouse-users::pages/language-resource.record_title');
    }

    public function getTitle(): string
    {
        return __('mediamouse-users::pages/language-resource.title');
    }

    protected function getTableReorderColumn(): ?string
    {
        return 'sort';
    }

    protected function isTablePaginationEnabledWhileReordering(): bool
    {
        return false;
    }

    /**
     * @throws Exception
     */
    protected function getActions(): array
    {
        return [
            CreateAction::make('create')
                ->label((__('mediamouse-users::pages/language-resource.create_language')))
                ->color('success')
                ->icon('heroicon-s-plus'),
        ];
    }
}
