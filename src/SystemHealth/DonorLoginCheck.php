<?php

namespace Mediamouse\Users\SystemHealth;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Models\LoginAttempt;
use Mediamouse\Users\SystemHealth\HealthCheckAbstract;
use phpDocumentor\Reflection\Types\This;

class DonorLoginCheck extends HealthCheckAbstract
{
    public function nextCheck($payload = null): Carbon
    {
        $lastDonorLogin =LoginAttempt::query()
            ->join('users', 'users.id', 'user_id')
            ->where('users.role', UserRole::MEMBER)
            ->where('login_attempts.status', LoginAttemptStatus::SUCCESSFUL)
            ->latest()
            ->value('created_at');

        return Carbon::create($lastDonorLogin)->addSeconds($payload?? 172800);
    }

    public function validUntil($payload = null): Carbon
    {
        return $this->nextCheck()->addSeconds(7200);
    }


    public static function check(mixed $payload = null): SystemHealthStatus
    {


        if (!self::donorExists($payload ?? 172800)) return SystemHealthStatus::WARNING;
        if (!self::donorExists($payload ? $payload * 2.5 : 432000)) return SystemHealthStatus::ERROR;

        return SystemHealthStatus::OK;

    }

    private static function donorExists(int $seconds): bool
    {

        return LoginAttempt::query()
            ->join('users', 'users.id', 'user_id')
            ->where('users.role', UserRole::MEMBER)
            ->where('login_attempts.status', LoginAttemptStatus::SUCCESSFUL)
            ->where('login_attempts.created_at', '>=', Carbon::now()->subSeconds($seconds))
            ->exists();

    }


}
