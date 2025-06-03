<?php

namespace Mediamouse\Users\UserCan;

use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Models\User;

abstract class UserCanValidatorAbstract
{
    protected string $for;

    public function for() {
        if(!isset($this->for)) {
            return static::class;
        }
        return $this->for;
    }

    abstract public function canDo(User $user, mixed $privilege, Model $record = null) : bool;

}
