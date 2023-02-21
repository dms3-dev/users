<?php

namespace Mediamouse\Users\Filament\Pages;

use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\UserManagementSettings;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Forms;

class ManageUserManagementSettings extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $title = 'Settings';
    protected static ?string $slug = 'user-management-settings';

    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 6;

    protected static string $settings = UserManagementSettings::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                    Forms\Components\Fieldset::make('Password settings')->columns(2)->columnSpan(1)->schema([
                        TextInput::make('password_min_length')
                            ->label('Min. length password')
                            ->columnSpan('full')
                            ->suffix('characters')
                            ->numeric()
                            ->required(),
                        TextInput::make('password_max_length')
                            ->label('Max. length password')
                            ->columnSpan('full')
                            ->suffix('characters')
                            ->numeric()
                            ->required(),
                        Toggle::make('special_characters')
                            ->label('Special character(s)'),
                        Toggle::make('numbers')
                            ->label('Number(s)'),
                        Toggle::make('capital_letters')
                            ->label('Capital letter(s)'),
                        Toggle::make('non_capital_letters')
                            ->label('Non capital letter(s)'),
                    ]),
                    Forms\Components\Fieldset::make('Security')->columns(1)->columnSpan(1)->schema([
                        TextInput::make('reset_password_every_x_days_with_2fa')
                            ->label('Reset password WITH 2FA')
                            ->columnSpan('full')
                            ->prefix('every')
                            ->suffix('days')
                            ->numeric()
                            ->required(),
                        TextInput::make('reset_password_every_x_days_without_2fa')
                            ->label('Reset password WITHOUT 2FA')
                            ->columnSpan('full')
                            ->prefix('every')
                            ->suffix('days')
                            ->numeric()
                            ->required(),
                        TextInput::make('max_login_attempts')
                            ->label('Max. login attempts')
                            ->columnSpan('full')
                            ->numeric()
                            ->required(),
                        TextInput::make('max_failed_login_attempts_per_ip')
                            ->label('Max. failed login attempts per IP')
                            ->columnSpan('full')
                            ->numeric()
                            ->required(),
                    ]),
                    Forms\Components\Fieldset::make('2FA')->columns(1)->columnSpan(1)->schema([
                        Forms\Components\CheckboxList::make('two_fa_MEMBER')
                            ->label('Member')
                            ->columns(3)
                                ->options(UserTwoFactor::class),
                        Forms\Components\CheckboxList::make('two_fa_ADMINISTRATOR')
                            ->columns(3)
                            ->label('Administrator')
                                ->options(UserTwoFactor::class),
                        Forms\Components\CheckboxList::make('two_fa_SA')
                            ->columns(3)
                                ->label('SA')
                                ->options(UserTwoFactor::class),
                    ]),
                ])
            ])
        ];
    }
}
