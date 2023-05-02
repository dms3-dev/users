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
        return Action::make('edit-country')
            ->label('Edit')
            ->color('warning')
            ->icon('heroicon-s-pencil')
            ->action(function (array $data, ViewCountry $livewire) {
                $country = $livewire->record;

                $country->iso = $data['iso'];
                $country->iso3 = $data['iso3'];
                $country->name = $data['name'];

                $country->save();
                redirect(CountryResource::getUrl('view', ['record' => $livewire->record]));
                Notification::make()
                    ->success()
                    ->title('Country has been edited successfully!')
                    ->send();

            })
            ->form(self::form());
    }
}
