<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers;

use Awcodes\TableRepeater\Components\TableRepeater;
use Exception;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;
use Mediamouse\Filament\Forms\Components\TextDisplay;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Models\User;

class UserMemberOfGroupRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('mediamouse-users::model/user-relation-model.title');
    }

    protected static ?string $recordTitleAttribute = 'Group';

    public function form(Schema $schema): Schema
    {

        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->label((__('mediamouse-users::pages/group-resource.name')))
                    ->columnSpan(1),
                Textarea::make('message')
                    ->label((__('mediamouse-users::pages/group-resource.message'))),

//                TableRepeater::make('fields')
//                    ->disabled()
//                    ->disableLabel()
//                    ->hideLabels()
//                    ->relationship()
//                    ->label('Name')
//                    ->columnSpan(1)
//                    ->schema([
//                        TextDisplay::make('name'),
//                        TextDisplay::make('description'),
//                    ]),

            ]);
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        /** @noinspection DuplicatedCode */
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->label((__('mediamouse-users::model/user-relation-model.username')))
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable( ['id', 'username']),
                Tables\Columns\TextColumn::make('name')
                    ->label((__('mediamouse-users::model/user-relation-model.full_name')))
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable( ['id', 'name']),
                Tables\Columns\TextColumn::make('role')
                    ->label((__('mediamouse-users::model/user-relation-model.user_role')))
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable( ['id', 'role']),
                Tables\Columns\TextColumn::make('groups')
                    ->label((__('mediamouse-users::model/user-relation-model.assigned_groups')))
                    ->formatStateUsing(
                        function(User $record) {
                            $groups = array();

                            foreach($record->groups as $group) {
                                $groups[] = $group->name;
                            }
                            return implode(',',  $groups);
                        }

                    )
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable( ['id', 'groups']),
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
