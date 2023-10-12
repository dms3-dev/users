<?php

namespace Mediamouse\Users\Filament\Pages;

use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Enums\UserTwoFactor;
use Mediamouse\Users\Filament\Pages\Actions\ToggleMaintenanceAction;
use Mediamouse\Users\Filament\Pages\Widgets\SystemHealthCards;
use Mediamouse\Users\Models\User;
use Mediamouse\Users\Settings\GlobalSettings as GlobalSettingsModel;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms;
use Mediamouse\Users\Models\SystemHealth as SystemHealthModel;
use Mediamouse\Users\Support\Date;

class SystemHealth extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-s-heart';
    protected static ?string $slug = 'system-health';

    protected static string $view = 'mediamouse-users::system-health';

    protected static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static function getNavigationLabel(): string
    {
        return __('mediamouse-users::pages/system-health.menu-label');
    }

    protected function getTitle(): string
    {
        return __('mediamouse-users::pages/system-health.menu-label');
    }


    protected function getFormSchema(): array
    {

        return [
            Forms\Components\Grid::make(2)->schema([


            ]),

        ];
    }

    protected function getTableQuery(): Builder|Relation
    {
        return SystemHealthModel::query()->whereNotNull('status');
    }

    protected function getActions(): array
    {
        return [

        ];
    }


    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('health_check')
                ->formatStateUsing(function(\Mediamouse\Users\Models\SystemHealth $record){
                    $class = $record->health_check;

                    $check = new $class($record->payload);
                    return $check->getName();
                }),
            TextColumn::make('status')
                ->sortable()
                ->searchable()
                ->toggleable()
                ->color(function (\Mediamouse\Users\Models\SystemHealth $record) {
                    if ($record->status == SystemHealthStatus::ERROR) return 'danger';
                    if ($record->status == SystemHealthStatus::WARNING) return 'warning';
                    return 'success';
                }),
            TextColumn::make('checked_at')
                ->sortable()
                ->searchable()
                ->toggleable()
                ->formatStateUsing(fn($state)=>$state? Carbon::parse($state)->format(Date::userDateFormat()) : ''),
            TextColumn::make('valid_until')
                ->sortable()
                ->searchable()
                ->toggleable()
                ->formatStateUsing(fn($state)=>$state? Carbon::parse($state)->format(Date::userDateFormat()) : ''),

        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SystemHealthCards::class,
        ];
    }
}
