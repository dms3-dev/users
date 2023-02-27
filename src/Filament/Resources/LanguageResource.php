<?php

namespace Mediamouse\Users\Filament\Resources;

use Exception;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Forms\Components;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\Actions\ViewAction;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\CreateLanguage;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\EditLanguage;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ListLanguages;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ViewLanguage;
use Mediamouse\Users\Filament\Resources\UserResource\RelationManagers\UserOverviewRelationManager;
use Mediamouse\Users\Models\Language;
use PHPUnit\TextUI\XmlConfiguration\Logging\TeamCity;
use Svg\Tag\Text;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-translate';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('iso')
                    ->maxLength(2)
                    ->required(),
                TextInput::make('name')
                    ->maxLength(20)
                    ->required(),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('iso')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Mediamouse\Users\Filament\Resources\LanguageResource\Pages\ListLanguages::route('/'),
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
