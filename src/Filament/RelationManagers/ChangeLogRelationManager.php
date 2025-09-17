<?php

namespace Mediamouse\Users\Filament\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Mediamouse\Users\Support\Date;

class ChangeLogRelationManager extends RelationManager
{
    protected static string $relationship = 'logs';

    protected static ?string $recordTitleAttribute = 'description';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('Log')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public ?string $tableSortDirection = 'desc';
    public ?string $tableSortColumn = 'created_at';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Changed at')
                    ->sortable( ['id','created_at'])
                    ->date(Date::userDateTimeFormat())
                    ->searchable('change_logs.created_at'),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Changed by')
                    ->sortable( ['id','created_by'])
                    ->formatStateUsing(fn($state) => $state ?? 'Guest')
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable( ['id','description'])
                    ->searchable('change_logs.description'),
                Tables\Columns\TextColumn::make('old_value')
                    ->label('From')
                    ->sortable( ['id','old_value'])
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable('change_logs.old_value'),
                Tables\Columns\TextColumn::make('new_value')
                    ->label('To')
                    ->sortable( ['id','new_value'])
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable('change_logs.new_value'),
                Tables\Columns\TextColumn::make('logitem')
                    ->label('Full')
                    ->toggleable()
                    ->view('filament.resources.donor-resource.relationmanager.logs.logitem'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->actions([
            ])
            ->bulkActions([
            ]);
    }

}
