<?php

namespace Mediamouse\Users\Filament\Resources;

use Exception;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\Actions\ViewAction;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages;
use Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers\GroupHasPolicyRelationManager;
use Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers\UserMemberOfGroupRelationManager;
use Mediamouse\Users\Models\Group;
use Mediamouse\Users\Policies\GroupPolicy;
use Mediamouse\Users\Support\Date;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 5;


    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? __('mediamouse-users::pages/group-resource.title');
    }

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {

        return $form
            ->schema([
                TextInput::make('key')
                    ->columns(1)
                    ->alphaNum()
                    ->unique(ignoreRecord: true)
                    ->label((__('mediamouse-users::model/group-model.group_key')))
                    ->maxLength('10')
                    ->required(),
                TextInput::make('name')
                    ->columns(1)
                    ->alphaNum()
                    ->label((__('mediamouse-users::model/group-model.group_name')))
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
    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label((__('mediamouse-users::model/group-model.group_key')))
                    ->toggleable()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label((__('mediamouse-users::model/group-model.group_name')))
                    ->sortable( ['key', 'name'])
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label((__('mediamouse-users::model/group-model.users_count')))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label((__('mediamouse-users::model/group-model.created_at')))
                    ->dateTime(Date::userDateTimeFormat())
                    ->toggleable()
                    ->sortable( ['key', 'created_at']),
            ])
            ->actions([
                ViewAction::make()->color('info')
                    ->visible(Filament::auth()->user()->hasPrivilege(GroupPolicy::class, PolicyPrivilege::VIEW)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn(Group $record) => $record->users_count === 0 ||
                        ($record->users_count === null && $record->users()->count() === 0 && Filament::auth()->user()->hasPrivilege(GroupPolicy::class, PolicyPrivilege::DELETE))
                ),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
//            'create' => Pages\CreateGroup::route('/create'),
//            'edit' => Pages\EditGroup::route('/{record}/edit'),
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
