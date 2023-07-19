<?php

namespace Mediamouse\Users\Filament\Resources;

use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Tables\Columns\CheckColumn;
use Mediamouse\Users\Enums\LanguageStatus;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Models\Language;
use Mediamouse\Users\Policies\LanguagePolicy;

class LanguageResource extends Resource
{
    protected static ?string $model = Language::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationIcon = 'heroicon-o-translate';
    protected static ?int $navigationSort = 5;

    /**
     * @return string|null
     */

    protected static function getNavigationLabel(): string
    {
        return static::$navigationLabel ?? __('mediamouse-users::pages/language-resource.title');
    }

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
                    ->label((__('mediamouse-users::pages/language-resource.name')))
                    ->maxLength(20)
                    ->required(),
                Select::make('status')
                    ->options([
                        LanguageStatus::ACTIVE->value => (__('mediamouse-users::pages/language-resource.active')),
                        LanguageStatus::INACTIVE->value => (__('mediamouse-users::pages/language-resource.inactive')),
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
                    ->label((__('mediamouse-users::pages/language-resource.name')))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
                CheckColumn::make('status', LanguageStatus::ACTIVE->value, LanguageStatus::INACTIVE->value)
                    ->label('Status')
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label((__('mediamouse-users::pages/language-resource.users_count')))
                    ->counts('users')
                    ->toggleable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(Filament::auth()->user()->hasPrivilege(LanguagePolicy::class, PolicyPrivilege::UPDATE))
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->visible(Filament::auth()->user()->hasPrivilege(LanguagePolicy::class, PolicyPrivilege::DELETE)),
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
