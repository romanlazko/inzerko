<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileFilled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->user()->isProfileFilled() && ! auth()->user()->isSuperAdmin()) {
            return redirect()->route('profile.edit')->with([
                'ok' => false,
                'description' => __('profile.fill_your_profile')
            ]);
        }

        return $next($request);
    }
}
