<?php

namespace Mediamouse\Users\Filament\Resources;

use Mediamouse\Users\Filament\Resources\UserResource\Pages;
use Mediamouse\Users\Models\User;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                        ->label('Full Name')
                        ->required(),
                Forms\Components\TextInput::make('email')
                        ->email()
                        ->required(),
                Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
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

                            Notification::make('verfied')
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
