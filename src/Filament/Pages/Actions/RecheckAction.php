<?php

namespace Mediamouse\Users\Filament\Pages\Actions;




use Filament\Notifications\Notification;
use Mediamouse\Filament\Tables\Actions\Action;
use Mediamouse\Users\Enums\SystemHealthStatus;

class RecheckAction
{

    public static function make(): Action
    {
        return Action::make('recheck')
            ->color('success')
            ->icon('heroicon-s-shield-check')
//            ->requiresConfirmation()
            ->action(function (\Mediamouse\Users\Models\SystemHealth $record) {
                    $record->check(true);

                    switch($record->status) {
                        case SystemHealthStatus::OK :
                            Notification::make('check-' . $record->health_check)
                                ->title($record->health_check::title($record))
                                ->body('The check passed')
                                ->success()
                                ->send();
                            break;
                        case SystemHealthStatus::WARNING :
                            Notification::make('check-' . $record->health_check)
                                ->title($record->health_check::title($record))
                                ->body('The check passed with warnings')
                                ->warning()
                                ->send();
                            break;
                        case SystemHealthStatus::ERROR :
                            Notification::make('check-' . $record->health_check)
                                ->title($record->health_check::title($record))
                                ->body('The check failed')
                                ->danger()
                                ->send();
                            break;
                    }
                }
            );
    }

}
