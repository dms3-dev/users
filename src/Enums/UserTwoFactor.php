<?php

namespace Mediamouse\Users\Enums;

use ArchTech\Enums\Options;

enum UserTwoFactor : string {
    use Options;

    case NONE = 'NONE';
    case EMAIL = 'EMAIL';
    case SMS = 'SMS';
}
