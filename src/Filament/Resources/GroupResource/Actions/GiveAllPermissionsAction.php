<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Actions;

use App\Filament\Resources\CountryResource;
use App\Filament\Resources\CountryResource\Pages\ViewCountry;
use App\Models\Country;
use Exception;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ViewGroup;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;
use Filament\Actions\Action;


class GiveAllPermissionsAction
{

    /**
     * @throws Exception
     */
    public static function make(): Action
    {
        return Action::make('Give-all')
            ->label('Give all')
            ->hiddenLabel()
            ->color('success')
            ->icon('heroicon-s-check')
            ->action(function ( GroupHasPolicy $record) {


                $record->view = true;
                $record->view_any = true;
                $record->delete = true;
                $record->create = true;
                $record->update = true;
                $record->force_delete = true;
                $record->restore = true;
                $record->reorder = true;

                $record->save();

            });
    }

}
