<?php

namespace Mediamouse\Users\Filament\Resources\UserResource\RelationManagers;

use Carbon\Carbon;
use Exception;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;

class LoginAttemptsRelationManager extends RelationManager
{
    protected static string $relationship = 'loginAttempts';

    protected static ?string $recordTitleAttribute = 'loginAttempt';

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        /** @noinspection DuplicatedCode */
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Attempt date')
                    ->formatStateUsing(fn (?Carbon $state) => $state?->format('j F Y H:i:s')),
                Tables\Columns\TextColumn::make('status')
                    ->label('Attempt status'),
                Tables\Columns\TextColumn::make('IP')
                    ->label('IP Address'),
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
