<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\Actions;


use Mediamouse\Users\Models\User;
use Mediamouse\Filament\Pages\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Laravel\Support\Arr;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserStatus;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Settings\UserManagementSettings;

class EditUserAction
{

    public static function make(): Action {
        return Action::make('edit')
            ->icon('heroicon-o-pencil')
            ->color('primary')
            ->form(self::form())
            ->mountUsing(fn(User $record, Forms\Form $form) => $form->fill($record->toArray()))
            ->action(function(User $record, array $data) {
                $record->fill($data);
                $record->save();
            });
    }


    public static function form(): array
    {
        return [
            Grid::make(2)->schema([
                Grid::make(1)->columnSpan(1)->schema([
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
                    Fieldset::make(__('mediamouse-users::pages/user-resource.about'))->columns(1)->columnSpan(1)->schema([
                        Select::make('language_iso')
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
                        CheckboxList::make('groups')
                            ->columns(2)
                            ->relationship('groups', 'name')
                            ->hiddenLabel(),
                    ]),
                    Forms\Components\Fieldset::make(__('mediamouse-users::pages/user-resource.security'))->columns(1)->columnSpan(1)->schema([
                        Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                            Forms\Components\Select::make('two_factor')
                                ->label((__('mediamouse-users::pages/user-resource.two_factor')))
                                ->enum(UserTwoFactor::class)
                                ->options(fn(\Filament\Forms\Get $get) => match ($get('role')) {
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
        ];
    }

}
