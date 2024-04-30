<?php

namespace Mediamouse\Users;

use Filament\Contracts\Plugin;
use Filament\Navigation\MenuItem;
use Filament\Navigation\UserMenuItem;
use Filament\Panel;
use Mediamouse\Users\Filament\Pages\GlobalSettings;

class UserPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-apex-charts';
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @phpstan-ignore-next-line */
        return filament(app(static::class)->getId());
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
//            \Mediamouse\Users\Filament\Resources\GroupResource::class,
            \Mediamouse\Users\Filament\Resources\UserResource::class,
//            \Mediamouse\Users\Filament\Resources\LanguageResource::class,
        ]);

        $panel->pages([

            \Mediamouse\Users\Filament\Pages\ManageUserManagementSettings::class,
            \Mediamouse\Users\Filament\Pages\GlobalSettings::class,
//            \Mediamouse\Users\Filament\Pages\UserSettings::class,
//            \Mediamouse\Users\Filament\Pages\ChangePassword::class,
//            \Mediamouse\Users\Filament\Pages\SystemHealth::class,
        ]);


        $panel->userMenuItems([
//            'account' => UserMenuItem::make()->url(route('filament.pages.user-settings')),
//            UserMenuItem::make()
//                ->label(__('mediamouse-users::pages/change-password.title'))
//                ->url(route('filament.pages.change-password'))
//                ->sort(1)
//                ->icon('heroicon-s-cog'),
            MenuItem::make()
                ->icon('heroicon-o-cog')
                ->label('Global Settings')
                ->url(route('filament.app.pages.global-settings'))

        ]);
    }

    public function boot(Panel $panel): void
    {
    }
}
