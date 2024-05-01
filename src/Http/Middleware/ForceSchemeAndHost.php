<?php

namespace Mediamouse\Users\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceSchemeAndHost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->getScheme() . '://' . $request->httpHost() !== env('APP_URL')) {
            return redirect(env('APP_URL') . $request->getRequestUri());
        }
        return $next($request);
    }
}
