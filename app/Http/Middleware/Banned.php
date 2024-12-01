<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Banned 
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()?->isBanned() AND ! request()->routeIs('profile.banned')) {
            return redirect()->route('profile.banned');
        }

        if (auth()->user()?->isNotBanned() AND request()->routeIs('profile.banned')) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}