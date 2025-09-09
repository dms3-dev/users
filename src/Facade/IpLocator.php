<?php

namespace Mediamouse\Users\Facade;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Mediamouse\Users\Models\IpLocatorListing;
use Mediamouse\Users\Models\Ip6LocatorListing;

class IpLocator
{

    public static function locateVisitor() {
        return self::locateIp(self::ip());
    }

    public static function locateIp(string $ip) {
        try {
            if (self::isIp4($ip)) {
                $nr = self::getIp4Nr($ip);

                $listing = IpLocatorListing::query()
                    ->where('start', '<=', $nr)
                    ->where('end', '>=', $nr)
                    ->first();

            } else {
                $nr = (self::getIp6Nr($ip));

                $listing = Ip6LocatorListing::query()
                    ->where('start', '<=', $nr)
                    ->where('end', '>=', $nr)
                    ->first();

            }

            if($listing !== null) return $listing->country_iso;
        } catch (\Throwable $e) {
            Bugsnag::notifyException($e);
        }

        return "GB";
    }


    public static function getIpNr($ip) {
        return self::getIp4Nr($ip);
    }

    public static function isIp4($ip) : bool {
        return strstr($ip, ':') === false;
    }

    public static function isIp6($ip) : bool {
        return !self::isIp4($ip);
    }

    public static function getIp4Nr($ip) {
        $split = explode(".", $ip);

        $nr = 0;

        $nr += $split[0] * pow(256, 3);
        $nr += $split[1] * pow(256, 2);
        $nr += $split[2] * pow(256, 1);
        $nr += $split[3] * pow(256, 0);

        return $nr;
    }

    public static function getIp6Nr($ip) {
        // inet_pton zet IPv6-adres om naar packed binary string (16 bytes)
        $bin = inet_pton($ip);
        if ($bin === false) {
            throw new \InvalidArgumentException("Invalid IPv6-adres: $ip");
        }
        // bin2hex geeft een hex-string terug
        return ($bin);
    }

    private static function ip() {
        if(env('APP_ENV') == 'local') return '185.249.42.7';

        return request()->ip();
    }

}
