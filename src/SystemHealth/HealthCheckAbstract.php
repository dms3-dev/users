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
    public function checkIntervalSeconds(int $seconds = 1800) : int { return  $seconds; }
    public function checkValidUntilInterval(int $seconds = 14400) : int { return $seconds; }






}

