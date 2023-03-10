<?php

namespace Mediamouse\Users\Filament\Resources;

use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Exception;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Mediamouse\Filament\Forms\Components\TextDisplay;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\Actions\ViewAction;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages;
use Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers\GroupHasPolicyRelationManager;
use Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers\UserMemberOfGroupRelationManager;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Models\GroupHasPolicy;
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
                        TextInput::make('key')
                            ->columns(1)
                            ->alphaNum()
                            ->unique(ignoreRecord: true)
                            ->label('Group key')
                            ->maxLength('10')
                            ->required(),
                        TextInput::make('name')
                            ->columns(1)
                            ->alphaNum()
                            ->label('Group name')
                            ->maxLength('100')
                            ->required(),
//                        Repeater::make('policies')
//                            ->relationship()
//                            ->disableLabel()
//                            ->disableItemMovement()
//                            ->disableItemDeletion()
//                            ->disableItemCreation()
//                            ->grid(4)
//                            ->columns(2)
//                            ->columnSpan('full')
//                            ->itemLabel(fn (array $state): ?string => Policy::query()->where('policy', $state['policy'])->value('name'))
//                            ->schema([
//                                Forms\Components\Toggle::make('view_any'),
//                                Forms\Components\Toggle::make('view'),
//                                Forms\Components\Toggle::make('create'),
//                                Forms\Components\Toggle::make('update'),
//                                Forms\Components\Toggle::make('delete'),
//                                Forms\Components\Toggle::make('restore'),
//                                Forms\Components\Toggle::make('force_delete'),
//                                Forms\Components\Toggle::make('reorder'),
//                            ])

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
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created on')
                    ->toggleable()
                    ->sortable(),
            ])
            ->actions([
                ViewAction::make()->color('info'),
                Tables\Actions\DeleteAction::make()->visible(fn(Group $record) =>
                                        $record->users_count === 0 || ($record->users_count === null && $record->users()->count() === 0)
                                ),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
//            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
            'view' => Pages\ViewGroup::route('/{record}/view'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            GroupHasPolicyRelationManager::class,
            UserMemberOfGroupRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
        ];
    }
}
