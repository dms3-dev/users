<?php

namespace Mediamouse\Users\Enums;

use ArchTech\Enums\Options;

enum UserStatus : string {
    use Options;

    case ACTIVE = 'ACTIVE';
    case LOCKED = 'LOCKED';
    case INACTIVE = 'INACTIVE';

    public static function translated(): array
    {
        $result = [];

        foreach(self::options() as $option) {
            $result[$option] = __('mediamouse-users::pages/user-resource.' . strtolower($option));
        }

        return $result;
    }
}
