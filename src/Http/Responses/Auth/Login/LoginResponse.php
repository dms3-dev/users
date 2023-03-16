<?php

namespace Mediamouse\Users\Http\Responses\Auth\Login;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as Responsable;

class LoginResponse implements Responsable
{

    public function toResponse($request)
    {
        return redirect()->intended(Filament::getUrl());
    }
}
