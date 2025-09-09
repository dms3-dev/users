<?php

namespace Mediamouse\Users\Http\Middleware;

use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Closure;

class StoreFilamentSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$guards)
    {

        $response = $next($request);

        try {
            if (Filament::auth()->user() !== null) {
                $user = Filament::auth()->user();
                $user->table_settings = session('tables');
                $user->save();
            }
        } catch (\Throwable $e) {}

        return $response;
    }
}
