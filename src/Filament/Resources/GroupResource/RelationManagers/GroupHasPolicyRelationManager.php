<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers;

use Awcodes\FilamentTableRepeater\Components\TableRepeater;
use Exception;
use Filament\Facades\Filament;
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
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Models\GroupHasPolicy;
use Mediamouse\Users\Models\Policy;
use Mediamouse\Users\Models\User;

class GroupHasPolicyRelationManager extends RelationManager
{
    protected static string $relationship = 'policies';

    public static function getTitle(): string
    {
        return __('mediamouse-users::pages/policy-relation.title');
    }

    protected static ?string $recordTitleAttribute = 'Policy';

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
                Tables\Columns\TextColumn::make('policy')
                    ->visible(Filament::auth()->user()->role === UserRole::SA)
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('mediamouse-users::pages/policy-relation.name')),
                Tables\Columns\CheckboxColumn::make('view_any'),
                Tables\Columns\CheckboxColumn::make('view'),
                Tables\Columns\CheckboxColumn::make('create'),
                Tables\Columns\CheckboxColumn::make('update'),
                Tables\Columns\CheckboxColumn::make('delete'),
                Tables\Columns\CheckboxColumn::make('force_delete'),
                Tables\Columns\CheckboxColumn::make('restore'),
                Tables\Columns\CheckboxColumn::make('reorder'),
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
