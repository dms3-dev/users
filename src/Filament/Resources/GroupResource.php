<?php

namespace Mediamouse\Users\Filament\Resources;

use Exception;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\Actions\ViewAction;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages;
use Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers\UserMemberOfGroupRelationManager;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\Policy;

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

                Forms\Components\Grid::make(1)->schema([
                    Forms\Components\Fieldset::make('Group information')->columns(1)->schema([
                        TextInput::make('key')
                            ->unique()
                            ->columns(1)
                            ->alphaNum()
                            ->label('Group key')
                            ->maxLength('10')
                            ->required(),
                        TextInput::make('name')
                            ->columns(1)
                            ->alphaNum()
                            ->label('Group name')
                            ->maxLength('100')
                            ->required(),
                        Forms\Components\CheckboxList::make('privileges')
                            ->options(PolicyPrivilege::class)

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
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Amount of users')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created on')
                    ->toggleable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make()->color('info'),
                Tables\Actions\DeleteAction::make()->visible(fn(Group $record) => $record->users_count === 0),
                ViewAction::make()->color('info'),
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
            UserMemberOfGroupRelationManager::class
        ];
    }

    public static function getWidgets(): array
    {
        return [
        ];
    }
}
