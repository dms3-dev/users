<?php


namespace Mediamouse\Users\Enums;

enum PolicyPrivilege : string {
    case VIEW_ANY = 'view_any';
    case VIEW = 'view';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case RESTORE = 'restore';
    case FORCE_DELETE = 'force_delete';
}
