<?php

namespace Mediamouse\Users\SystemHealth;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Card;
use Illuminate\Support\Facades\DB;
use Mediamouse\Users\Enums\LoginAttemptStatus;
use Mediamouse\Users\Enums\SystemHealthStatus;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Models\LoginAttempt;
use Mediamouse\Users\SystemHealth\HealthCheckAbstract;
use phpDocumentor\Reflection\Types\This;

class DonorLoginCheck extends HealthCheckAbstract
{
    public function nextCheck(): Carbon
    {
        $lastDonorLogin =LoginAttempt::query()
            ->join('users', 'users.id', 'user_id')
            ->where('users.role', UserRole::MEMBER)
            ->where('login_attempts.status', LoginAttemptStatus::SUCCESSFUL)
            ->latest()
            ->value('login_attempts.created_at');

        return Carbon::create($lastDonorLogin)->addSeconds($this->payload?? 172800);
    }

    public function validUntil(): Carbon
    {
        return $this->nextCheck()->addSeconds(7200);
    }

    public function getName(): string
    {
        return 'Recent donor login';
    }



    public function check(): SystemHealthStatus
    {

        $status = SystemHealthStatus::OK;

        if (!self::donorExists($this->payload ?? 172800)) $status = SystemHealthStatus::WARNING;
        if (!self::donorExists($this->payload ? $this->payload * 2.5 : 432000)) $status = SystemHealthStatus::ERROR;

        return $status;

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

    public function getCards(): array
    {
        return [
            Card::make('Last Donor Login',LoginAttempt::query()
                ->join('users', 'users.id', 'user_id')
                ->where('users.role', UserRole::MEMBER)
                ->where('login_attempts.status', LoginAttemptStatus::SUCCESSFUL)
                ->latest()
                ->value('login_attempts.created_at')
                )
        ];
    }


}
