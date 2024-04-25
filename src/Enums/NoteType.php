<?php

namespace Mediamouse\Users\Enums;

use ArchTech\Enums\Options;

enum NoteType : string {
    use Options;

    case NOTE = 'NOTE';
    case TASK = 'TASK';
}
