<?php

namespace Mediamouse\Users\Enums;

enum PasswordResetStatus : string {
    case CREATED = 'CREATED';
    case ACTIVE = 'ACTIVE';
    case USED = 'USED';
    case EXPIRED = 'EXPIRED';
}

