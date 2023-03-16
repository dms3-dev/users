<?php

namespace Mediamouse\Users\Http\Responses\Auth\Login;

use Filament\Http\Responses\Auth\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Redirector;

class TimeOutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        return redirect()->route('filament.auth.login')
                        ->with('login.error', 'timeout');
    }
}
