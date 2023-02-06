<?php

namespace Mediamouse\Users\Enums;

enum UserTwoFactor : string {
    case NONE = 'NONE';
    case EMAIL = 'EMAIL';
    case SMS = 'SMS';
}
