<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Actions;

use App\Filament\Resources\CountryResource;
use App\Filament\Resources\CountryResource\Pages\ViewCountry;
use App\Models\Country;
use Exception;
use Filament\Schemas\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\BulkActions\BulkAction;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ViewGroup;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;


class BulkRemoveAllPermissionsAction
{

    /**
     * @throws Exception
     */
    public static function make(): BulkAction
    {
        return BulkAction::make('Bulk-remove-all')
            ->label('Remove all')
            ->hiddenLabel()
            ->color('warning')
            ->icon('heroicon-s-x-mark')
            ->action(function (Collection $records) {

                $records->each(function ($record) {
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



            });
    }

}
