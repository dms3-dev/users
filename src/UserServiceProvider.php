<?php

namespace Mediamouse\Users;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Livewire\Livewire;
use Mediamouse\Users\Console\Commands\UpdatePolicies;
use Mediamouse\Users\Http\Livewire\Auth\Challenge;
use Mediamouse\Users\Http\Livewire\Auth\EnterNewPassword;
use Mediamouse\Users\Http\Livewire\Auth\ForgotPassword;
use Mediamouse\Users\Http\Livewire\Auth\Login;
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
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name('mediamouse-users')
            ->hasViews()
            ->hasRoutes('web')
            ->hasCommands([UpdatePolicies::class])
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
//            Filament::registerNavigationGroups([
//                NavigationGroup::make()
//                    ->label('User Management')
//                    ->icon('heroicon-s-user'),
//            ]);
        });
    }
}
