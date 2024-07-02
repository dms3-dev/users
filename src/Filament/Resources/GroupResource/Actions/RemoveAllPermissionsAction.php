<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Actions;

use App\Filament\Resources\CountryResource;
use App\Filament\Resources\CountryResource\Pages\ViewCountry;
use App\Models\Country;
use Exception;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ViewGroup;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;
use Filament\Tables\Actions\Action;


class RemoveAllPermissionsAction
{

    /**
     * @throws Exception
     */
    public static function make(): Action
    {
        return Action::make('Remove-all')
            ->label('Remove all')
            ->hiddenLabel()
            ->color('warning')
            ->icon('heroicon-s-x-mark')
            ->action(function ( GroupHasPolicy $record) {


                $record->view = false;
                $record->view_any = false;
                $record->delete = false;
                $record->create = false;
                $record->update = false;
                $record->force_delete = false;
                $record->restore = false;
                $record->reorder = false;

                $record->save();

            });
    }

}
