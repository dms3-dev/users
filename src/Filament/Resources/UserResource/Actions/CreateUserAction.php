<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Actions;


use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Mediamouse\Filament\Pages\Actions\Action;
use Filament\Forms\Form;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Models\User;
use Carbon\Carbon;

class CreateUserAction extends EditUserAction
{


    public static function make(): Action
    {
        return Action::make('create-user')
            ->color('success')
            ->icon('heroicon-o-plus')
            ->form(self::form())
            ->action(function (array $data) {
                dd($data);
                $newUser = new User();
                $newUser->username = $data['username'];
                $newUser->name = $data['name'];
                $newUser->email = $data['email'];
                $newUser->language_iso = $data['language_iso'];
                $newUser->role = $data['role'] ?? UserRole::ADMINISTRATOR;
//                $newUser->groups = $data['groups'];
                $newUser->two_factor = $data['two_factor'];
                $newUser->status = $data['status'];
//                $newUser->email_verified_at = Carbon::now();

                $newUser->save();

                if(class_exists(Bugsnag::class) && env('APP_ENV') !== 'local') {
                    Bugsnag::notifyError( 'SystemHealthChecks','An new admin was created', function (\Bugsnag\Report $report) {
                        $report->setSeverity('info');
                    });
                }


            }

            );

    }

}
