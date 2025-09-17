<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\RelationManagers;

use Awcodes\TableRepeater\Components\TableRepeater;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Mediamouse\Filament\Forms\Components\TextDisplay;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Filament\Resources\GroupResource\Actions\BulkGiveAllPermissionsAction;
use Mediamouse\Users\Filament\Resources\GroupResource\Actions\BulkRemoveAllPermissionsAction;
use Mediamouse\Users\Filament\Resources\GroupResource\Actions\GiveAllPermissionsAction;
use Mediamouse\Users\Filament\Resources\GroupResource\Actions\RemoveAllPermissionsAction;
use Mediamouse\Users\Models\GroupHasPolicy;
use Mediamouse\Users\Models\Policy;
use Mediamouse\Users\Models\User;

class GroupHasPolicyRelationManager extends RelationManager
{
    protected static string $relationship = 'policies';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('mediamouse-users::pages/policy-relation.title');
    }

    protected static ?string $recordTitleAttribute = 'Policy';

    public function form(Schema $schema): Schema
    {

        return $schema
            ->columns(1)
            ->components([
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
               GiveAllPermissionsAction::make(),
               RemoveAllPermissionsAction::make(),

            ])
            ->bulkActions([
                BulkGiveAllPermissionsAction::make(),
                BulkRemoveAllPermissionsAction::make(),
            ]);
    }
}
