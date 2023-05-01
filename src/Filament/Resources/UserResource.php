<?php

namespace Mediamouse\Users\Filament\Resources;

use Carbon\Carbon;
use Closure;
use Exception;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Mediamouse\Filament\Tables\Actions\ViewAction;
use Mediamouse\Laravel\Livewire\Request;
use Mediamouse\Laravel\Support\Arr;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserStatus;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Filament\Resources\UserResource\Pages;
use Mediamouse\Users\Filament\Resources\UserResource\Pages\CreateUser;
use Mediamouse\Users\Filament\Resources\UserResource\Pages\EditUser;
use Mediamouse\Users\Filament\Resources\UserResource\Pages\ViewUser;
use Mediamouse\Users\Filament\Resources\UserResource\RelationManagers\LoginAttemptsRelationManager;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\UserManagementSettings;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        Forms\Components\Fieldset::make('User information')->columns(1)->columnSpan(1)->schema([
                            TextInput::make('username')->required()->maxLength(50),
                            TextInput::make('name')->label('Full name')->required()->maxLength(255),
                            TextInput::make('email')->required()->email()->maxLength(255)->unique(ignoreRecord: true),
                        ]),
                        Forms\Components\Fieldset::make('About')->columns(1)->columnSpan(1)->schema([
                            Forms\Components\Select::make('language_iso')
                                ->label('Language')
                                ->required()
                                ->options(function () {
                                    return Language::query()->where('status', LanguageStatus::ACTIVE->value)->orderBy('sort')->pluck('name', 'iso');
                                }),
                            Forms\Components\Select::make('role')
                                ->options(UserRole::class)
                                ->enum(UserRole::class)
                                ->required(),
                        ]),
                    ]),
                    Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        Forms\Components\Fieldset::make('Groups')->columns(1)->columnSpan(1)->schema([
                            Forms\Components\CheckboxList::make('groups')
                                ->columns(2)
                                ->relationship('groups', 'name')
                                ->inlineLabel()
                                ->disableLabel(),
                        ]),
                        Forms\Components\Fieldset::make('Security')->columns(1)->columnSpan(1)->schema([
                            Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                                Forms\Components\Select::make('two_factor')
                                    ->enum(UserTwoFactor::class)
                                    ->options(Arr::setKeysEqualToValues(Arr::combine(
                                        app(UserManagementSettings::class)->two_fa_MEMBER,
                                        app(UserManagementSettings::class)->two_fa_ADMINISTRATOR,
                                        app(UserManagementSettings::class)->two_fa_SA
                                    )))
                                        ->rules([
                                            function (CreateUser|ViewUser|EditUser $livewire) {
                                                return function (string $attribute, $value, Closure $fail) use ($livewire) {
                                                    $role = Request::hasUpdatedValue('data.role', $livewire->record?->role->value);
                                                    $allowed_values = [];
                                                    switch($role) {
                                                        case UserRole::NONE->value :
                                                            $allowed_values = UserTwoFactor::options();
                                                            break;
                                                        case UserRole::MEMBER->value :
                                                            $allowed_values = app(UserManagementSettings::class)->two_fa_MEMBER;
                                                            break;
                                                        case UserRole::ADMINISTRATOR->value :
                                                            $allowed_values = app(UserManagementSettings::class)->two_fa_ADMINISTRATOR;
                                                            break;
                                                        case UserRole::SA->value :
                                                            $allowed_values = app(UserManagementSettings::class)->two_fa_SA;
                                                            break;
                                                    }

                                                    if(!in_array($value, $allowed_values)) {
                                                        $the_values = implode(', ' , $allowed_values);
                                                        $fail("For {$role} only {$the_values} are allowed!");
                                                    }


                                                };
                                            },
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label('Account status')
                                    ->enum(UserStatus::class)
                                    ->options([
                                        UserStatus::ACTIVE->value => 'Active',
                                        UserStatus::INACTIVE->value => 'Inactive',
                                        UserStatus::LOCKED->value => 'Locked',
                                    ])
                                    ->required()
                            ]),
                        ]),
                    ]),
                ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Full name')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('E-mail verified at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('two_factor')
                    ->label('2FA')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('language.name')
                    ->label('Language')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Account status')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('two_factor')
                    ->label('2FA')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('groups.name')
                    ->label('Group(s)')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->formatStateUsing(
                        function(User $record) {
                            $groups = array();

                            foreach($record->groups as $group) {
                                $groups[] = $group->name;
                            }
                            return implode(', ',  $groups);
                        }

                    ),
                Tables\Columns\TextColumn::make('lastLoginAttempt.created_at')
                    ->label('Last login attempt')
                    ->formatStateUsing(fn(?Carbon $state) => $state?->format('j F Y H:i:s'))
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn(User $record) => $record->email_verified_at === null)
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->email_verified_at = Carbon::now();
                        $record->save();

                        Notification::make('verified')
                            ->iconColor('success')
                            ->title('User is verified')
                            ->icon('heroicon-o-check')
                            ->send();
                    }),
                Tables\Actions\ViewAction::make(),
//                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                ViewAction::make()->color('info'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}/view'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            LoginAttemptsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            UserResource\Widgets\UserOverview::class,
        ];
    }
}
