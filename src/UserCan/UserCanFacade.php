<?php

namespace Mediamouse\Users\UserCan;

class UserCanFacade
{
    public static function registerValidator(UserCanValidatorAbstract $validator) {
        app(UserCan::class)->addValidator($validator);
    }

}
