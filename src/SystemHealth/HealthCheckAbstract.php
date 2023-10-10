<?php

namespace Mediamouse\Users\SystemHealth;


use Carbon\Carbon;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Models\SystemHealth;
use Mediamouse\Users\Models\SystemHealthStats;

abstract class HealthCheckAbstract
{
    protected mixed $payload;

    public function __construct(mixed $payload = null) {
        $this->payload = $payload;
    }

    public static abstract function check(mixed $payload);
    public function nextCheck() : Carbon { return Carbon::now()->addSeconds(1800); }
    public function validUntil() : Carbon { return Carbon::now()->addSeconds(7200); }


    public function getCards() : array { return []; }




}

