<?php

namespace Mediamouse\Users\Filament\Resources;

use Exception;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Filament\Resources\UserResource\Pages;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                        ->label('Full Name')
                        ->required(),
                Forms\Components\TextInput::make('username')
                        ->label('Username')
                        ->required(),
                Forms\Components\TextInput::make('email')
                        ->email()
                        ->required(),
                Forms\Components\Select::make('language_iso')
                    ->label('Language')
                    ->required()
                    ->options(function() {
                        return Language::query()->where('status', LanguageStatus::ACTIVE->value)->orderBy('sort')->pluck('name', 'iso');
                    }),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
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
                Tables\Columns\TextColumn::make('language_iso')
                    ->label('ISO')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\Action::make('verify')
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->visible(fn(User $record) => $record->email_verified_at === null)
                        ->requiresConfirmation()
                        ->action(function(User $record) {
                            $record->email_verified_at = Carbon::now();
                            $record->save();

                            Notification::make('verified')
                                ->iconColor('success')
                                ->title('User is verified')
                                ->icon('heroicon-o-check')
                                ->send();
                        }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
