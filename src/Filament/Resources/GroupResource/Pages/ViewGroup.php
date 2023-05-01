<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Pages;

use Filament\Resources\Form;
use Mediamouse\Filament\Forms\Components\TextInput;
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
        return "Group {$this->record->name} ({$this->record->key})";
    }

    /**
     * @throws \Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
//            Actions\DeleteAction::make(),
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

    protected function form(Form $form): Form
    {
        return $form->schema([

        ]);
    }

}

