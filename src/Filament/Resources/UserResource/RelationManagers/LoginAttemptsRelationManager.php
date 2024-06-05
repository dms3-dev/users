<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\RelationManagers;

use Carbon\Carbon;
use Exception;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LoginAttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'loginAttempts';

    protected static ?string $recordTitleAttribute = 'loginAttempt';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
            ]);
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        /** @noinspection DuplicatedCode */
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label((__('mediamouse-users::pages/user-resource.attempt_date')))
                    ->formatStateUsing(fn (?Carbon $state) => $state?->format('j F Y H:i:s')),
                Tables\Columns\TextColumn::make('status')
                    ->label((__('mediamouse-users::pages/user-resource.attempt_status'))),
                Tables\Columns\TextColumn::make('IP')
                    ->label((__('mediamouse-users::pages/user-resource.ip_address'))),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
