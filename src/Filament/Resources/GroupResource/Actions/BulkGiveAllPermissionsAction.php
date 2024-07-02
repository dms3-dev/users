<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Actions;

use App\Filament\Resources\CountryResource;
use App\Filament\Resources\CountryResource\Pages\ViewCountry;
use App\Models\Country;
use Exception;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\BulkActions\BulkAction;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ViewGroup;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;


class BulkGiveAllPermissionsAction
{

    /**
     * @throws Exception
     */
    public static function make(): BulkAction
    {
        return BulkAction::make('Bulk-give-all')
            ->label('Remove all')
            ->hiddenLabel()
            ->color('success')
            ->icon('heroicon-s-check')
            ->action(function (Collection $records) {

                $records->each(function ($record) {
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



            });
    }

}
