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
        $this->init();
    }

    abstract public function check() : SystemHealthStatus;
    abstract public function getName(SystemHealthStatus|null $healthStatus) : string;


    public function nextCheck() : Carbon { return Carbon::now()->addSeconds(1800); }
    public function validUntil() : Carbon { return Carbon::now()->addSeconds(7200); }


    protected function init() : void {}
    public function getCards() : array { return []; }

}

