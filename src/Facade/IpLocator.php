<?php

namespace Mediamouse\Users\Facade;

use Mediamouse\Users\Models\IpLocatorListing;

class IpLocator
{

    public static function locateVisitor() {
        return self::locateIp(self::ip());
    }

    public static function locateIp(string $ip) {
        $nr = self::getIpNr($ip);

        $listing = IpLocatorListing::query()
                        ->where('start', '<=', $nr)
                        ->where('end', '>=', $nr)
                        ->first();

        return $listing?->country_iso;
    }


    private static function getIpNr($ip) {
        $split = explode(".", $ip);

        $nr = 0;

        $nr += $split[0] * pow(256, 3);
        $nr += $split[1] * pow(256, 2);
        $nr += $split[2] * pow(256, 1);
        $nr += $split[3] * pow(256, 0);

        return $nr;
    }

    private static function ip() {
        if(env('APP_ENV') == 'local') return '185.249.42.7';

        return request()->ip();
    }

}
