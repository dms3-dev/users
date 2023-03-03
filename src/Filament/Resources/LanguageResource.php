<?php

namespace Mediamouse\Users\Filament\Resources;

use Exception;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\Columns\CheckColumn;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Models\Language;

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
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(2)
                    ->alpha(),
                TextInput::make('name')
                    ->maxLength(20)
                    ->required(),
                Select::make('status')
                    ->options([
                        LanguageStatus::ACTIVE->value => 'Active',
                        LanguageStatus::INACTIVE->value => 'In Active',
                    ])
                    ->default(LanguageStatus::ACTIVE)
                    ->enum(LanguageStatus::class)
                    ->label('Status'),
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
                    ->label('ISO')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                CheckColumn::make('status', LanguageStatus::ACTIVE->value, LanguageStatus::INACTIVE->value)
                    ->label('Status')
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
