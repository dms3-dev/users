<?php

namespace Mediamouse\Users\SystemHealth;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Models\LoginAttempt;
use Mediamouse\Users\SystemHealth\HealthCheckAbstract;

class BackEndLoginCheck extends HealthCheckAbstract
{

    public function nextCheck() : Carbon { return Carbon::now()->addSeconds(300); }
    public function validUntil() : Carbon { return Carbon::now()->addSeconds(1800); }
    public function getName(): string
    {
        return 'More than 10 failed backend logins';
    }


    public function check(): SystemHealthStatus
    {
        $logins = LoginAttempt::query()->where('status',LoginAttemptStatus::FAILED)
            ->where('created_at', '>=', Carbon::now()->subSeconds($this->payload?? 3600))
            ->count();
        if ($logins >= 10) return SystemHealthStatus::ERROR;


        return SystemHealthStatus::OK;

    }


//
}
