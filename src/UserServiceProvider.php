<?php

namespace Mediamouse\Users;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UserServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mediamouse-users')
            ->hasViews()
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

        Filament::serving(function () {
            Filament::registerNavigationGroups([
                NavigationGroup::make()
                    ->label('User Management')
                    ->icon('heroicon-s-user'),
            ]);
        });
    }
}
