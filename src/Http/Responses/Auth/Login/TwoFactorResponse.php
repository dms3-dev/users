<?php

namespace Mediamouse\Users\Http\Responses\Auth\Login;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Redirector;

class TwoFactorResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        return redirect()->route('mediamouse-users.auth.2fa');
    }
}
