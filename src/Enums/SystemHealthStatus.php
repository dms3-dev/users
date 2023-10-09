<?php


namespace Mediamouse\Users\Enums;

use ArchTech\Enums\Options;

enum SystemHealthStatus : string {
    use Options;

    case ERROR = 'ERROR';
    case WARNING = 'WARNING';
    case OK = 'OK';

}
