<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Pages;

use Mediamouse\Users\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

/**
 * @property \Mediamouse\Users\Models\User record
 */
class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
    protected static ?string $title = 'User';

    public function getTitle(): string
    {
        return __('mediamouse-users::pages/user-resource.record_title') . ' ' . $this->record->name;
    }

    /**
     * @throws \Exception
     */
    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
        ];
    }

    public function mountTableAction() {

    }
    public function edit() {

    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserResource\Widgets\UserOverview::class,
        ];
    }

}

