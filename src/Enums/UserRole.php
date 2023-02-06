<?php

namespace Mediamouse\Users\Enums;

enum UserRole : string {
    case NONE = 'NONE';
    case MEMBER = 'MEMBER';
    case ADMINISTRATOR = 'ADMINISTRATOR';
    case SA = 'SA';
}
