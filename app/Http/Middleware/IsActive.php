<?php

namespace App\Http\Middleware;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\HelperClasses\TResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsActive
{
    use TResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = MyApp::Classes()->getUser();
        if (!is_null($user) && $user->is_active) {
            return $next($request);
        }
        throw new MainException("this user is currently not active");
    }
}
