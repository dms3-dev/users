<?php

namespace Mediamouse\Users\Facade;

use Mediamouse\Users\Models\SystemHealth as SystemHealthModel;
use Mediamouse\Users\Enums\SystemHealthStatus;

class SystemHealth
{
    private static bool $warning, $error;

    public static function healthColor() {
        if(self::isError()) return 'danger';
        if (self::isWarning()) return 'warning';

        return 'success';
    }

    public static function isError() {
        if(!isset(self::$error)) {
            self::$error = SystemHealthModel::query()->where('status',SystemHealthStatus::ERROR)->exists();
        }
        return self::$error;
    }

    public static function isWarning() {
        if(!isset(self::$warning)) {
            self::$warning = !self::isError() && SystemHealthModel::query()->where('status',SystemHealthStatus::WARNING)->exists();
        }
        return self::$warning;
    }

    public static function isSuccess() {
        return !self::isError() && !self::isWarning();
    }

}
