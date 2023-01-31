<?php

namespace Mediamouse\Users;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UserServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mediamouse-users')
            ->hasMigrations([
                'create_languages_table',
                'create_users_table',
            ])
            ;
    }
}