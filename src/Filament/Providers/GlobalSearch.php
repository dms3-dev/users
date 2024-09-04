<?php

namespace Mediamouse\Users\Filament\Providers;

use Filament\Facades\Filament;
use Filament\GlobalSearch\DefaultGlobalSearchProvider;
use Filament\GlobalSearch\GlobalSearchResults;

class GlobalSearch extends DefaultGlobalSearchProvider
{
    public static function sortResources($a, $b) {
        if(isset($a::$globalSearchSort) && isset($b::$globalSearchSort)) {
            if($a::$globalSearchSort == $b::$globalSearchSort) $return = 0;
            else $return = ($a::$globalSearchSort < $b::$globalSearchSort) ? -1 : 1;
        }
        elseif(isset($a::$globalSearchSort)) return -1;
        elseif(isset($b::$globalSearchSort)) return 1;
        elseif($a == $b) $return = 0;
        else $return = ($a < $b) ? -1 : 1;

        return $return;
    }

    public function getResults(string $query): ?GlobalSearchResults
    {
        $builder = GlobalSearchResults::make();
        $resources = [];

        foreach (Filament::getResources() as $resource) {
            if (!$resource::canGloballySearch()) {
                continue;
            }
            $resources[] = $resource;
        }

        usort($resources, [static::class, 'sortResources']);

        foreach($resources as $resource) {

            $resourceResults = $resource::getGlobalSearchResults($query);

            if (! $resourceResults->count()) {
                continue;
            }

            $builder->category($resource::getPluralModelLabel(), $resourceResults);
        }

        return $builder;
    }

}
