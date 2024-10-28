<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Locale {

    public function __construct(private Application $app, private Request $request) {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = Auth::user()?->locale ?? session('locale', config('app.locale'));

        $this->app->setLocale($locale);

        return $next($request);
    }

}