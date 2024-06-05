<?php

namespace Mediamouse\Users\Helper;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;
use Mediamouse\Users\Models\User;

class Login
{

    public static function login(User $user): bool {
        Filament::auth()->login($user);
        Auth::login($user);

        request()->session()->regenerate();

        return true;
    }


}
