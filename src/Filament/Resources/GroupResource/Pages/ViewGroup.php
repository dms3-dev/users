<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Pages;

use Filament\Facades\Filament;
use Filament\Forms\Form;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Mediamouse\Users\Policies\GroupPolicy;

/**
 * @property \Mediamouse\Users\Models\Group record
 */
class ViewGroup extends ViewRecord
{
    protected static string $resource = GroupResource::class;
    protected static ?string $title = 'Group';

    public function getTitle(): string
    {
        return __('mediamouse-users::pages/group-resource.record_title') . ' ' . $this->record->name . ' (' . $this->record->key . ')';
    }

    /**
     * @throws \Exception
     */
    protected function getActions(): array
    {
        return [
            GroupResource\Actions\EditGroup::make()
                ->label((__('mediamouse-users::pages/group-resource.edit')))
                ->visible(Filament::auth()->user()->hasPrivilege(GroupPolicy::class, PolicyPrivilege::UPDATE)),
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

//    protected function form(Form $form): Form
//    {
//        return $form->schema([
//
//        ]);
//    }

}

