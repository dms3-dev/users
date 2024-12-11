<?php

namespace Mediamouse\Users;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\UserMenuItem;
use Filament\Panel;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Mediamouse\Users\Console\Commands\CheckSystemHealth;
use Mediamouse\Users\Console\Commands\UpdateIpDatabase;
use Mediamouse\Users\Console\Commands\UpdatePolicies;
use Mediamouse\Users\Database\CreateViews;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Filament\Pages\SettingsSections\CsvExportSettingsSection;
use Mediamouse\Users\Filament\Pages\SettingsSections\DateSettingsSection;
use Mediamouse\Users\Filament\Pages\SettingsSections\ErrorCodesSettingsSection;
use Mediamouse\Users\Filament\Pages\SettingsSections\MaintenanceModeSettingsSection;
use Mediamouse\Users\Filament\Pages\SettingsSections\NumberSettingsSection;
use Mediamouse\Users\Filament\Pages\SettingsSections\Sections;
use Mediamouse\Users\Filament\Pages\SystemHealth;
use Mediamouse\Users\Filament\Pages\Widgets\SystemHealthCards;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Http\Livewire\Auth\Challenge;
use Mediamouse\Users\Http\Livewire\Auth\EnterNewPassword;
use Mediamouse\Users\Http\Livewire\Auth\ForgotPassword;
use Mediamouse\Users\Http\Livewire\Auth\Login;
use Mediamouse\Users\Http\Middleware\StoreFilamentSettings;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\UserManagementSettings;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Illuminate\Support\Facades\Event;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

class UserServiceProvider extends PackageServiceProvider
{

    public static string $name = 'mediamouse-users';
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
        \Mediamouse\Users\Filament\Pages\SystemHealth::class,
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasConfigFile('mediamouse-users')
            ->hasRoutes('web')
            ->hasCommands([
                    UpdatePolicies::class,
                    CheckSystemHealth::class,
                    UpdateIpDatabase::class
                ])
            ->hasTranslations()
            ->hasMigrations([
                'create_languages_table',
                'add_fields_to_users_table',
                'create_groups_table',
                'create_policies_table',
                'create_notes_table',
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
                'maintenance_settings',
                'maintenance_text',
                'create_system_health_table',
                'create_system_health_stats_table',
                'alter_policy_relations_to_cascasde',
                'create_change_log_table',
                'error_codes_settings',
                'create_ip_locator_table',
                'add_settings_to_user_table',
            ]);
    }

    public function boot()
    {
        parent::boot();

        $kernel = app(\Illuminate\Contracts\Http\Kernel::class);

        $kernel->pushMiddleWare(StoreFilamentSettings::class);

        Sections::add(DateSettingsSection::class);
        Sections::add(CsvExportSettingsSection::class);
        Sections::add(NumberSettingsSection::class);
        Sections::add(MaintenanceModeSettingsSection::class);
        Sections::add(ErrorCodesSettingsSection::class);

        Livewire::component('login', Login::class);
        Livewire::component('challenge', Challenge::class);
        Livewire::component('forgot-password', ForgotPassword::class);
        Livewire::component('enter-new-password', EnterNewPassword::class);
        Livewire::component('system-health-cards', SystemHealthCards::class);

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => \view('mediamouse-users::system-health-badge'),
        );

        Filament::serving(function (): void {

            if (Filament::auth()->user() !== null) {
                /** @var User $current_user */
                $current_user = Filament::auth()->user();
                App::setLocale($current_user->language_iso);
            }

            if (app()->isDownForMaintenance()) {
                Filament::registerRenderHook('global-search.start', fn() => View::make('mediamouse-users::maintenance-badge', [
                    'secret' => (new UserManagementSettings())->maintenance_secret
                ]));
            }

            Filament::registerRenderHook('global-search.start', fn() => View::make('mediamouse-users::system-health-badge', [
            ]));

        });

        Event::listen(function (CommandFinished $event) {
            CreateViews::event($event);
        });
    }
}
