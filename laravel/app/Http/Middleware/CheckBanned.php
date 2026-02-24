<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->check() && $auth->user()->is_banned){
            auth()->logout();

            $request->session()->invalidate();
            $request->session()->generateToken();

            return redirect()->route('login')->with('error', 'The error was been banned');

        }
        return $next($request);
    }
}
