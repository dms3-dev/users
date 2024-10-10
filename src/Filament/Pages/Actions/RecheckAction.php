<?php

namespace Mediamouse\Users\Filament\Pages\Actions;




use Mediamouse\Filament\Tables\Actions\Action;

class RecheckAction
{

    public static function make(): Action
    {
        return Action::make('recheck')
            ->color('success')
            ->icon('heroicon-s-shield-check')
            ->requiresConfirmation()
            ->action(function (\Mediamouse\Users\Models\SystemHealth $record) {
                $record->check(true);
                refreshPage();
                }
            );
    }

}
