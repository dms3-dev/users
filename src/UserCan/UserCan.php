<?php

namespace Mediamouse\Users\UserCan;

use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Enums\UserRole;
use Mediamouse\Users\Models\User;

class UserCan
{
    private static array $validators = [];

    public function check(User $user, mixed $privilege, Model $record = null) {
        if($user->role === UserRole::SA) return true;

        foreach (self::$validators as $key => $validator) {
            if($key === get_class($privilege)) {
                return $validator->canDo($user, $privilege, $record);
            }
        }
        
        return false;
    }

    public function addValidator(UserCanValidatorAbstract $validator) {
        self::$validators[$validator->for()] = $validator;
    }

}
