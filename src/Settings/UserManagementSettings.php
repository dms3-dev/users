<?php

namespace Mediamouse\Users\Settings;

use Spatie\LaravelSettings\Settings;

class UserManagementSettings extends Settings
{
//    password settings

    public int $password_min_length;
    public int $password_max_length;
    public bool $special_characters;
    public bool $numbers;
    public bool $capital_letters;
    public bool $non_capital_letters;

//    security

    public int $reset_password_every_x_days_with_2fa ;
    public int $reset_password_every_x_days_without_2fa ;
    public int $max_login_attempts ;
    public int $max_failed_login_attempts_per_ip ;

//    2fa

    public array $two_fa_MEMBER;
    public array $two_fa_ADMINISTRATOR;
    public array $two_fa_SA;

    public static function group(): string
    {
        return 'mediamouse-users';
    }
}
