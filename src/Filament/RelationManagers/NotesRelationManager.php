<?php

namespace Mediamouse\Users\Filament\RelationManagers;

use App\Models\User;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Mediamouse\Users\Enums\NoteStatus;
use Mediamouse\Users\Enums\NoteType;
use Mediamouse\Users\Enums\PolicyPrivilege;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserStatus;
use Mediamouse\Users\Models\Note;
use Mediamouse\Users\Policies\NotePolicy;
use Mediamouse\Users\Support\Date;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $recordTitleAttribute = 'Note';

    public static function form(Form $form): Form
    {
        return $form
            ->columns(2)
            ->schema([
                Grid::make(4)->schema([
                    Forms\Components\Textarea::make('content')
                        ->required()
                        ->rows(15)
                        ->columnSpan(3),
                    Grid::make(1)->columnSpan(1)->schema([
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options(NoteType::class)
                            ->reactive()
                            ->default(NoteType::NOTE),
                        Forms\Components\DatePicker::make('milestone_at')
                            ->format(Date::userDateFormat())
                            ->reactive()
                            ->visible(fn(\Closure $get) => $get('type') === NoteType::TASK->value || $get('type') === NoteType::TASK)
                            ->label('Due date')
                            ->closeOnDateSelection(),
                        Forms\Components\Select::make('status')
                            ->required()
                            ->options(NoteStatus::class)
                            ->reactive()
                            ->visible(fn(\Closure $get) => $get('type') === NoteType::TASK->value || $get('type') === NoteType::TASK)
                            ->default(NoteStatus::OPEN),
                        Forms\Components\Select::make('assigned_to')
                            ->label('Assign task to')
                            ->reactive()
                            ->visible(fn(\Closure $get) => $get('type') === NoteType::TASK->value || $get('type') === NoteType::TASK)
                            ->options(User::query()
                                ->whereIn('role', [UserRole::ADMINISTRATOR, UserRole::SA])
                                ->where('status', UserStatus::ACTIVE)
                                ->pluck('name', 'id')
                            ),

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
            ->defaultSort('created_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->sortable( ['id', 'type'])
                    ->toggleable()
                    ->searchable()
                    ->label('Type'),
                Tables\Columns\TextColumn::make('content')
                    ->sortable( ['id', 'content'])
                    ->toggleable()
                    ->searchable()
                    ->formatStateUsing(function (Note $record) {
                        if(strlen($record->content) > 50) {
                            return substr($record->content, 0, 47) . '...';
                        }
                        return substr($record->content, 0, 50);
                    }),
                Tables\Columns\TextColumn::make('milestone_at')
                    ->dateTime(Date::userDateTimeFormat())
                    ->searchable()
                    ->sortable( ['id', 'milestone_at'])
                    ->formatStateUsing(fn(Note $record, mixed $state) => $record->type === NoteType::TASK ? $state : '')
                    ->toggleable()
                    ->label('Due date'),
                Tables\Columns\TextColumn::make('status')
                    ->sortable( ['id', 'status'])
                    ->toggleable()
                    ->formatStateUsing(fn(Note $record, mixed $state) => $record->type === NoteType::TASK ? $state : '')
                    ->searchable()
                    ->label('Status'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(NoteType::class),
                Tables\Filters\SelectFilter::make('status')
                    ->options(NoteStatus::class)
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->color('success')
                    ->icon('heroicon-s-plus')
                    ->visible(Filament::auth()->user()->hasPrivilege(NotePolicy::class, PolicyPrivilege::CREATE)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('warning')
                    ->visible(Filament::auth()->user()->hasPrivilege(NotePolicy::class, PolicyPrivilege::UPDATE)),
                Tables\Actions\DeleteAction::make()
                    ->visible(Filament::auth()->user()->hasPrivilege(NotePolicy::class, PolicyPrivilege::DELETE)),
                Tables\Actions\Action::make('resolve')
                    ->label('Resolve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn(Note $record) => $record->type === NoteType::TASK && $record->status !== NoteStatus::RESOLVED && Filament::auth()->user()->hasPrivilege(NotePolicy::class, PolicyPrivilege::UPDATE))
                    ->requiresConfirmation()
                    ->action(function(Note $record) {
                        $record->status = NoteStatus::RESOLVED;
                        $record->save();

                        Notification::make('note-resolved')
                                ->title('The task is set to resolved')
                                ->success()
                                ->send();

                        refreshPage();
                    })
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(Filament::auth()->user()->hasPrivilege(NotePolicy::class, PolicyPrivilege::DELETE)),
            ]);
    }
}
