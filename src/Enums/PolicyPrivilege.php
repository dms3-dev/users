<?php


namespace Mediamouse\Users\Enums;

enum PolicyPrivilege : string {
    case VIEW_ANY = 'VIEW_ANY';
    case VIEW = 'VIEW';
    case CREATE = 'CREATE';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';
    case RESTORE = 'RESTORE';
    case FORCE_DELETE = 'FORCE_DELETE';
}
