<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers;

use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Exception;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Mediamouse\Filament\Forms\Components\TextDisplay;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Models\User;

class UserMemberOfGroupRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $recordTitleAttribute = 'Group';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->columnSpan(1),
                Textarea::make('message')
                    ->label('Message'),

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
    public static function table(Table $table): Table
    {
        /** @noinspection DuplicatedCode */
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('username')
                    ->label('Username')
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Full name')
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label('User role')
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('groups')
                    ->label('Assigned group(s)')
                    ->formatStateUsing(
                        function($state) {
                            $groups = array();

                            foreach($state as $group) {
                                $groups[] = $group->name;
                            }
                            return implode(',',  $groups);
                        }

                    )
                    ->toggleable()
                    ->alignLeft()
                    ->searchable()
                    ->sortable(),
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
