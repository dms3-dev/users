<?php

namespace Mediamouse\Users\Http\Responses\Auth\Login;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Responsable;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Mediamouse\Users\Http\Livewire\Auth\Login;

class TimeOutResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        return redirect()->to(Login::getUrl())
                        ->with('login.error', 'timeout');
    }
}
