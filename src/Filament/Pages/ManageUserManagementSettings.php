<?php

namespace Mediamouse\Users\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Schemas\Schema;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\UserManagementSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Forms;
use Filament\Schemas;

class ManageUserManagementSettings extends SettingsPage
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-cog';
    protected static ?string $title = 'Settings';
    protected static ?string $slug = 'user-management-settings';

    protected static \UnitEnum|string|null $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 6;

    public static function getNavigationLabel(): string
    {
        return __('mediamouse-users::pages/security-settings.navigation_label');
    }

    public function getTitle(): string
    {
        return __('mediamouse-users::pages/security-settings.navigation_label');
    }

    protected static string $settings = UserManagementSettings::class;

    public function __construct($id = null)
    {
        if(Filament::auth()?->user()?->role !== UserRole::SA) abort(403);
//        parent::__construct($id);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Filament::auth()?->user()?->role === UserRole::SA;
    }

    public function form(Schema $schema) : Schema {
        return $schema->schema($this->getFormSchema());
    }

    protected function getFormSchema(): array
    {
        return [
            Schemas\Components\Grid::make(2)->schema([
                Schemas\Components\Grid::make(1)->columnSpan(1)->schema([
                    Schemas\Components\Fieldset::make(__('mediamouse-users::pages/security-settings.security'))
                        ->columns(1)->columnSpan(1)
//                        ->label(__('mediamouse-users::pages/security_settings.security'))
                        ->schema([
//                        TextInput::make('max_login_attempts')
//                            ->label('Max. login attempts')
//                            ->columnSpan('full')
//                            ->numeric()
//                            ->required(),
                        TextInput::make('max_failed_login_attempts_per_ip')
                            ->label(__('mediamouse-users::pages/security-settings.max_failed_login_attempts_per_ip'))
                            ->columnSpan('full')
                            ->inlineLabel()
                            ->numeric()
                            ->required(),
                    ]),
                    Schemas\Components\Fieldset::make(__('mediamouse-users::pages/security-settings.two_factor_auth'))->columns(1)->columnSpan(1)->schema([
                        Forms\Components\CheckboxList::make('two_fa_MEMBER')
                            ->label(__('mediamouse-users::pages/security-settings.member'))
                            ->inlineLabel()
                            ->columns(3)
                                ->options(UserTwoFactor::class),
                        Forms\Components\CheckboxList::make('two_fa_ADMINISTRATOR')
                            ->columns(3)
                            ->label(__('mediamouse-users::pages/security-settings.administrator'))
                            ->inlineLabel()
                                ->options(UserTwoFactor::class),
                        Forms\Components\CheckboxList::make('two_fa_SA')
                            ->columns(3)
                                ->label('SA')
                            ->inlineLabel()
                                ->options(UserTwoFactor::class),
                    ]),
                ]),

                Schemas\Components\Fieldset::make(__('mediamouse-users::pages/security-settings.password_requirements'))->columns(8)->columnSpan(1)->schema([
                    TextInput::make('reset_password_every_x_days_with_2fa')
                        ->label(__('mediamouse-users::pages/security-settings.reset_password_with_2fa'))
                        ->inlineLabel()
                        ->columnSpan('full')
                        ->prefix(__('mediamouse-users::pages/security-settings.every'))
                        ->suffix(__('mediamouse-users::pages/security-settings.days'))
                        ->numeric()
                        ->required(),
                    TextInput::make('reset_password_every_x_days_without_2fa')
                        ->label(__('mediamouse-users::pages/security-settings.reset_password_without_2fa'))
                        ->inlineLabel()
                        ->columnSpan('full')
                        ->prefix(__('mediamouse-users::pages/security-settings.every'))
                        ->suffix(__('mediamouse-users::pages/security-settings.days'))
                        ->numeric()
                        ->required(),
                    TextInput::make('password_min_length')
                        ->label(__('mediamouse-users::pages/security-settings.password_min_length'))
                        ->inlineLabel()
//                        ->disableLabel()
                        ->columnSpan('full')
                        ->suffix(__('mediamouse-users::pages/security-settings.characters'))
                        ->numeric()
                        ->required(),
                    TextInput::make('password_max_length')
                        ->label(__('mediamouse-users::pages/security-settings.password_max_length'))
                        ->inlineLabel()
//                        ->disableLabel()
                        ->columnSpan('full')
                        ->suffix(__('mediamouse-users::pages/security-settings.characters'))
                        ->numeric()
                        ->required(),
                    Toggle::make('special_characters')
                        ->label(__('mediamouse-users::pages/security-settings.special_characters'))
                    ->columnSpan(4),
                    Toggle::make('numbers')
                        ->label(__('mediamouse-users::pages/security-settings.numbers'))
                        ->columnSpan(4),
                    Toggle::make('capital_letters')
                        ->label(__('mediamouse-users::pages/security-settings.capital_letters'))
                        ->columnSpan(4),
                    Toggle::make('non_capital_letters')
                        ->label(__('mediamouse-users::pages/security-settings.non_capital_letters'))
                        ->columnSpan(4),
                ]),
            ])
        ];
    }
}
