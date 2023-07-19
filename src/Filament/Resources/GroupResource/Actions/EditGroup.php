<?php

namespace Mediamouse\Users\Filament\Resources\GroupResource\Actions;

use App\Filament\Resources\CountryResource;
use App\Filament\Resources\CountryResource\Pages\ViewCountry;
use App\Models\Country;
use Exception;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Mediamouse\Filament\Forms\Components\TextInput;
use Mediamouse\Filament\Pages\Actions\Action;
use Mediamouse\Users\Filament\Resources\GroupResource;
use Mediamouse\Users\Filament\Resources\GroupResource\Pages\ViewGroup;
use Mediamouse\Users\Models\Group;


class EditGroup
{
    /**
     * @throws Exception
     */
    public static function form(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    TextInput::make('key')
                        ->columns(1)
                        ->alphaNum()
                        ->default(fn (Group $record) => $record->key)
                        ->columnSpan(1)
                        ->unique(ignoreRecord: true)
                        ->label('Group key')
                        ->maxLength('10')
                        ->required(),
                    TextInput::make('name')
                        ->columnSpan(1)
                        ->default(fn (Group $record) => $record->name)
                        ->alphaNum()
                        ->label('Group name')
                        ->maxLength('100')
                        ->required(),
                ]),

        ];
    }

    /**
     * @throws Exception
     */
    public static function make(): Action
    {
        return Action::make('edit-group')
            ->label('Edit')
            ->color('warning')
            ->icon('heroicon-s-pencil')
            ->action(function (array $data, ViewGroup $livewire) {
                $group = $livewire->record;

                $group->key = $data['key'];
                $group->name = $data['name'];

                $group->save();
                redirect(GroupResource::getUrl('view', ['record' => $livewire->record]));
                Notification::make()
                    ->success()
                    ->title('Group has been edited successfully!')
                    ->send();

            })
            ->form(self::form());
    }
}
