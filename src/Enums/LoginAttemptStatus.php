<?php

namespace Mediamouse\Users\Enums;

enum LoginAttemptStatus : string {
    case CREATED = 'CREATED';
    case SUCCESSFUL = 'SUCCESSFUL';
    case UNLOCKED = 'UNLOCKED';
    case PENDING_2FA = 'PENDING_2FA';
    case CANCELLED = 'CANCELLED';
    case FAILED = 'FAILED';
}
