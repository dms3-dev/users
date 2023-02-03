<?php

namespace Mediamouse\Users\Role;

enum Role : string {
    case NONE = 'NONE';
    case MEMBER = 'MEMBER';
    case ADMINISTRATOR = 'ADMINISTRATOR';
    case SA = 'SA';
}
