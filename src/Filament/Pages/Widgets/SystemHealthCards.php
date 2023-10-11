<?php

namespace Mediamouse\Users\Filament\Pages\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Database\Eloquent\Collection;
use Mediamouse\Users\Models\SystemHealth;

class SystemHealthCards extends StatsOverviewWidget {

    protected function getCards(): array
    {
        $cards = [];
        foreach ($this->getChecks() as $check){
            $class = $check->health_check;
            $check = new $class($check->payload);

            $cards = array_merge($cards,$check->getCards());
        }
        return $cards;
    }

    private function getChecks(): Collection|array
    {
        return SystemHealth::query()->get();
    }
    
}

