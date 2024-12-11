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

    public static function defaultPayload() { return null; }
    public static function title(SystemHealth $health) { return 'Health Check'; }

    public static function register(bool $check_exists = true) {
        if(!$check_exists || !SystemHealth::query()->where('health_check', static::class)->exists()) {
            $newSystem = new SystemHealth();
            $newSystem->health_check = static::class;
            $newSystem->payload = static::defaultPayload();
            $newSystem->update_after = Carbon::now();

            $newSystem->save();
        }
    }

}

