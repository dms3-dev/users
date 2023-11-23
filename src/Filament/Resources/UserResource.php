<?php

namespace Mediamouse\Users\Filament\Resources;

use Carbon\Carbon;
use Closure;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Mediamouse\Filament\Tables\Actions\ViewAction;
use Mediamouse\Laravel\Livewire\Request;
use Mediamouse\Laravel\Support\Arr;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Enums\PolicyPrivilege;
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
use Mediamouse\Users\Policies\LanguagePolicy;
use Mediamouse\Users\Policies\UserPolicy;
use Mediamouse\Users\Settings\UserManagementSettings;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 5;

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? __('mediamouse-users::pages/user-resource.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-resource.user_information'))->columns(1)->columnSpan(1)->schema([
                            TextInput::make('username')
                                ->label((__('mediamouse-users::model/user-model.username')))
                                ->required()
                                ->maxLength(50),
                            TextInput::make('name')
                                ->label((__('mediamouse-users::model/user-model.full_name')))
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label((__('mediamouse-users::model/user-model.email_address')))
                                ->required()
                                ->email()
                                ->maxLength(255)
                                ->unique(ignoreRecord: true),
                        ]),
                        Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-resource.about'))->columns(1)->columnSpan(1)->schema([
                            Forms\Components\Select::make('language_iso')
                                ->label((__('mediamouse-users::model/user-model.language')))
                                ->required()
                                ->options(function () {
                                    return Language::query()->where('status', LanguageStatus::ACTIVE->value)->orderBy('sort')->pluck('name', 'iso');
                                }),
                            Forms\Components\Select::make('role')
                                ->label((__('mediamouse-users::model/user-model.role')))
                                ->options(Filament::auth()->user()->role == UserRole::ADMINISTRATOR ? [UserRole::ADMINISTRATOR] : UserRole::class)
                                ->default(UserRole::ADMINISTRATOR)
                                ->visible(Filament::auth()->user()->role == UserRole::SA)
                                ->enum(UserRole::class)
                                ->reactive()
                                ->required(),
                        ]),
                    ]),
                    Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        Forms\Components\Fieldset::make(__('mediamouse-users::model/user-model.groups'))->columns(1)->columnSpan(1)->schema([
                            Forms\Components\CheckboxList::make('groups')
                                ->columns(2)
                                ->relationship('groups', 'name')
                                ->inlineLabel()
                                ->disableLabel(),
                        ]),
                        Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-resource.security'))->columns(1)->columnSpan(1)->schema([
                            Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                                Forms\Components\Select::make('two_factor')
                                    ->label((__('mediamouse-users::pages/user-resource.two_factor')))
                                    ->enum(UserTwoFactor::class)
                                    ->options(fn(Closure $get) => match ($get('role')) {
                                        UserRole::ADMINISTRATOR->value => Arr::setKeysEqualToValues(app(UserManagementSettings::class)->two_fa_ADMINISTRATOR),

                                        UserRole::SA->value => Arr::setKeysEqualToValues(app(UserManagementSettings::class)->two_fa_SA),
                                        UserRole::MEMBER->value => Arr::setKeysEqualToValues(app(UserManagementSettings::class)->two_fa_MEMBER),
                                        default =>
                                        Arr::setKeysEqualToValues(Arr::combine(
                                            app(UserManagementSettings::class)->two_fa_MEMBER,
                                            app(UserManagementSettings::class)->two_fa_ADMINISTRATOR,
                                            app(UserManagementSettings::class)->two_fa_SA
                                        ))
                                    }
                                    )
//                                    ->rules([
//                                        function (CreateUser|ViewUser|EditUser $livewire) {
//                                            return function (string $attribute, $value, Closure $fail) use ($livewire) {
//                                                $role = Request::hasUpdatedValue('data.role', $livewire->record?->role->value);
//                                                $allowed_values = [];
//                                                switch ($role) {
//                                                    case UserRole::NONE->value :
//                                                        $allowed_values = UserTwoFactor::options();
//                                                        break;
//                                                    case UserRole::MEMBER->value :
//                                                        $allowed_values = app(UserManagementSettings::class)->two_fa_MEMBER;
//                                                        break;
//                                                    case UserRole::ADMINISTRATOR->value :
//                                                        $allowed_values = app(UserManagementSettings::class)->two_fa_ADMINISTRATOR;
//                                                        break;
//                                                    case UserRole::SA->value :
//                                                        $allowed_values = app(UserManagementSettings::class)->two_fa_SA;
//                                                        break;
//                                                }
//
//                                                if (!in_array($value, $allowed_values)) {
//                                                    $the_values = implode(', ', $allowed_values);
//                                                    $fail("For {$role} only {$the_values} are allowed!");
//                                                }
//
//
//                                            };
//                                        },
//                                    ])
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label((__('mediamouse-users::pages/user-resource.account_status')))
                                    ->enum(UserStatus::class)
                                    ->options(UserStatus::translated())
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
                    ->label((__('mediamouse-users::model/user-model.username')))
                    ->toggleable()
                    ->sortable( ['id', 'username'])
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label((__('mediamouse-users::model/user-model.full_name')))
                    ->sortable( ['id', 'name'])
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label((__('mediamouse-users::model/user-model.email_address')))
                    ->toggleable()
                    ->sortable( ['id', 'email'])
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label((__('mediamouse-users::model/user-model.email_verified_at')))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable( ['id', 'email_verified_at']),
                Tables\Columns\TextColumn::make('two_factor')
                    ->label((__('mediamouse-users::model/user-model.two_factor')))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable( ['id', 'two_factor']),
                Tables\Columns\TextColumn::make('role')
                    ->label((__('mediamouse-users::model/user-model.role')))
                    ->toggleable()
                    ->sortable( ['id', 'role'])
                    ->searchable(),
                Tables\Columns\TextColumn::make('language.name')
                    ->label((__('mediamouse-users::model/user-model.language')))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable( ['id', 'language.name'])
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label((__('mediamouse-users::pages/user-resource.account_status')))
                    ->toggleable()
                    ->sortable( ['id', 'status'])
                    ->searchable(),
                Tables\Columns\TextColumn::make('groups.name')
                    ->label((__('mediamouse-users::model/user-model.groups')))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->formatStateUsing(
                        function (User $record) {
                            $groups = array();

                            foreach ($record->groups as $group) {
                                $groups[] = $group->name;
                            }
                            return implode(', ', $groups);
                        }

                    ),
                Tables\Columns\TextColumn::make('lastLoginAttempt.created_at')
                    ->label((__('mediamouse-users::pages/user-resource.last_login_attempt')))
                    ->formatStateUsing(fn(?Carbon $state) => $state?->format('j F Y H:i:s'))
                    ->sortable( ['id', 'lastLoginAttempt.created_at']),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                    ->label((__('mediamouse-users::pages/user-resource.verify')))
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn(User $record) => $record->email_verified_at === null && Filament::auth()->user()->hasPrivilege(UserPolicy::class, PolicyPrivilege::UPDATE))
                    ->requiresConfirmation()
                    ->action(function (User $record) {
                        $record->email_verified_at = Carbon::now();
                        $record->save();

                        Notification::make('verified')
                            ->iconColor('success')
                            ->title((__('mediamouse-users::pages/user-resource.user_is_verified')))
                            ->icon('heroicon-o-check')
                            ->send();
                    }),
                Tables\Actions\ViewAction::make()
                    ->visible(Filament::auth()->user()->hasPrivilege(UserPolicy::class, PolicyPrivilege::VIEW)),
//                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(User $record) => Filament::auth()->user()->hasPrivilege(UserPolicy::class, PolicyPrivilege::DELETE) && $record->id !== Filament::auth()->user()->id),
                ViewAction::make()->color('info')
                    ->visible(Filament::auth()->user()->hasPrivilege(UserPolicy::class, PolicyPrivilege::VIEW)),
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
