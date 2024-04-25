<?php

namespace Mediamouse\Users\Enums;

use ArchTech\Enums\Options;

enum NoteStatus : string {
    use Options;

    case OPEN = 'OPEN';
    case PENDING = 'PENDING';
    case RESOLVED = 'RESOLVED';
}
