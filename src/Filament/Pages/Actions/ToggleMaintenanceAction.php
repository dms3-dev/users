<?php

namespace Mediamouse\Users\Filament\Pages\Actions;


use App\View\Components\Form\Toggle\Toggle;
use Illuminate\Foundation\Http\MaintenanceModeBypassCookie;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Mediamouse\Filament\Pages\Actions\Action;
use Illuminate\Support\Str;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\UserManagementSettings;

class ToggleMaintenanceAction
{

    public static function make(): Action
    {
        return Action::make('toggle-maintenance')
            ->label(__('mediamouse-users::pages/global-settings.toggle-maintenance-button'))
            ->requiresConfirmation()
            ->action(function (array $data) {
//                dd(route('home'));

                if (app()->isDownForMaintenance()) {
                    Artisan::call('up');
                    if ($data['generate_new_secret']) {
                        $settings = new UserManagementSettings();
                        $settings->maintenance_secret = (string) Str::uuid();

                        $settings->save();
                    }
                    refreshPage();
                    return;
                }

                $secret = (new UserManagementSettings())->maintenance_secret;

//dump(MaintenanceModeBypassCookie::create($secret));
                $cookie = MaintenanceModeBypassCookie::create($secret);

                $arr_cookie_options = array (
                    'expires' => time() + 86400,
                    'path' => '/',
                            'domain' => request()->httpHost(), // leading dot for compatibility or use subdomain
                    'secure' => false,     // or false
                    'httponly' => false,    // or false
                    'samesite' => 'Lax' // None || Lax  || Strict
                );

                setcookie(
                             $cookie->getName(),
                             $cookie->getValue(),
                    $arr_cookie_options
                    );

                Artisan::call('down', ['--secret' => $secret]);

                refreshPage();
            })
            ->form([
                \Filament\Forms\Components\Toggle::make('generate_new_secret')
                    ->visible(app()->isDownForMaintenance())
                    ->offColor('danger')
                    ->offIcon('heroicon-s-x')
                    ->onColor('success')
                    ->onIcon('heroicon-s-check'),
            ]);
    }


}
