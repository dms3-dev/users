<?php

namespace Mediamouse\Users\Enums;

enum UserStatus : string {
    case ACTIVE = 'ACTIVE';
    case LOCKED = 'LOCKED';
    case INACTIVE = 'INACTIVE';
}
