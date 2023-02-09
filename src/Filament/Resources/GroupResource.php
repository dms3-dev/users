<?php

namespace Mediamouse\Users\Filament\Resources;

use Carbon\Carbon;
use Exception;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Forms\Components;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\User;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Grid::make(1)->columnSpan(1)->schema([
                        Forms\Components\Fieldset::make('Group information')->columns(1)->columnSpan(1)->schema([
                            TextInput::make('key')
                                ->label('Group key')
                                ->maxLength('10')
                                ->required(),
                            TextInput::make('name')
                                ->label('Group name')
                                ->maxLength('100')
                                ->required(),
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
                Tables\Columns\TextColumn::make('key')
                    ->label('Group key')
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Group name')
                    ->sortable()
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created on')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('users')
                    ->label('Amount of users')
                    ->toggleable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
            'view' => Pages\ViewGroup::route('/{record}/view'),
        ];
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getWidgets(): array
    {
        return [
        ];
    }
}
