<?php

namespace Mediamouse\Users\Filament\Widgets;

use App\Filament\Resources\DonorResource;
use App\Filament\Resources\HouseholdResource;
use App\Models\Donor;
use App\Models\Household;
use Filament\Facades\Filament;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Mediamouse\Users\Enums\NoteStatus;
use Mediamouse\Users\Enums\NoteType;
use Mediamouse\Users\Models\Note;
use Mediamouse\Users\Support\Date;

class TasksOverview extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return Note::query()
            ->where('type', NoteType::TASK->value)
            ->whereIn('status', [NoteStatus::OPEN->value, NoteStatus::PENDING->value])
            ->where('assigned_to', Filament::auth()->user()->id)
            ->orderBy('milestone_at');
    }

    protected int | string | array $columnSpan = [

        'sm' => 12,
        'md' => 12,
        'lg' => 12,
        'xl' => 12,
        '2xl' => 12,
    ];

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->contentGrid($this->getTableContentGrid());
    }

    protected function getTableContentGrid(): ?array
    {
        return [
            'sm' => 2,
            'md' => 2,
            'lg' => 2,
            'xl' => 3,
            '2xl' => 4,
        ];
    }

//    protected function getTableRecordsPerPage(): int
//    {
//        return 12;
//    }

    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [12, 24, 36, 48,];
    }


    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\Layout\Stack::make([
                Tables\Columns\TextColumn::make('qualified_name')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('milestone_at')
                    ->dateTime(Date::userDateTimeFormat())
                    ->searchable()
                    ->extraAttributes(['class' => 'mb-3'])
                    ->label('Due date'),
                Tables\Columns\TextColumn::make('content')
                    ->searchable()
                    ->limit(200)
                    ->formatStateUsing(function (Tables\Columns\TextColumn $column, Note $record) {
                        if(strlen($record->content) > $column->getCharacterLimit()) {
                            return substr($record->content, 0, $column->getCharacterLimit()) . '...';
                        }
                        return substr($record->content, 0, $column->getCharacterLimit());
                    }),

            ]),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->label(fn(Note $record) => 'View ' . $record->notable->labelText())
                ->color(fn(Note $record) =>  $record->notable->labelColor())
                ->url(fn(Note $record) =>  $record->notable->noteListLink()),
            Tables\Actions\Action::make('resolve')
                ->requiresConfirmation()
                ->color('success')
                ->icon('heroicon-s-check')
                ->requiresConfirmation()
                ->action(function(Note $record) {
                    $record->status = NoteStatus::RESOLVED;
                    $record->save();
                })
        ];
    }
}
