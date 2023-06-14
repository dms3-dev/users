<?php

namespace Mediamouse\Users;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\UserMenuItem;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Mediamouse\Users\Console\Commands\UpdatePolicies;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Http\Livewire\Auth\Challenge;
use Mediamouse\Users\Http\Livewire\Auth\EnterNewPassword;
use Mediamouse\Users\Http\Livewire\Auth\ForgotPassword;
use Mediamouse\Users\Http\Livewire\Auth\Login;
use Mediamouse\Users\Models\User;
use Spatie\LaravelPackageTools\Package;
use Filament\PluginServiceProvider;

class UserServiceProvider extends PluginServiceProvider
{

    protected array $resources = [
        \Mediamouse\Users\Filament\Resources\GroupResource::class,
        \Mediamouse\Users\Filament\Resources\UserResource::class,
        \Mediamouse\Users\Filament\Resources\LanguageResource::class,
    ];

    protected array $pages = [
        \Mediamouse\Users\Filament\Pages\ManageUserManagementSettings::class,
        \Mediamouse\Users\Filament\Pages\GlobalSettings::class,
        \Mediamouse\Users\Filament\Pages\UserSettings::class,
        \Mediamouse\Users\Filament\Pages\ChangePassword::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('mediamouse-users')
            ->hasViews()
            ->hasRoutes('web')
            ->hasCommands([UpdatePolicies::class])
            ->hasTranslations()
            ->hasMigrations([
                'create_languages_table',
                'add_fields_to_users_table',
                'create_groups_table',
                'create_policies_table',
                'create_privileges_table',
                'create_group_has_policies_table',
                'create_login_attempts_table',
                'create_password_resets_table',
                'create_passwords_table',
                'create_user_memberof_group_table',
                'user_management_settings',
                'add_languages',
                'global_settings',
                'create_user_settings_table',
                'global_settings_csv',
            ])
            ;
    }

    public function boot()
    {
        parent::boot();

        Livewire::component(Login::getName(), Login::class);
        Livewire::component(Challenge::getName(), Challenge::class);
        Livewire::component(ForgotPassword::getName(), ForgotPassword::class);
        Livewire::component(EnterNewPassword::getName(), EnterNewPassword::class);

        Filament::serving(function () {

            Filament::registerUserMenuItems([
                // ...
            ]);

            Filament::registerUserMenuItems([
                'account' => UserMenuItem::make()->url(route('filament.pages.user-settings')),
                UserMenuItem::make()
                    ->label(__('mediamouse-users::pages/change-password.title'))
                    ->url(route('filament.pages.change-password'))
                    ->sort(1)
                    ->icon('heroicon-s-cog'),
            ]);

            if(Filament::auth()->user() !== null) {
                /** @var User $current_user */
                $current_user = Filament::auth()->user();
                App::setLocale($current_user->language_iso);

                if($current_user->role == UserRole::SA) {
                    Filament::registerUserMenuItems([
                        UserMenuItem::make()
                            ->label(__('mediamouse-users::pages/global-settings.title'))
                            ->url(route('filament.pages.global-settings'))
                            ->sort(2)
                            ->icon('heroicon-s-cog'),
                    ]);
                }
            }
        });
    }
}
