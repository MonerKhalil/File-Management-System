<?php

namespace App\Http\Middleware;

use App\HelperClasses\MyApp;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class userCheckAuth
{
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
        if (is_null($user)){
            throw new AuthenticationException();
        }
        return $next($request);
    }
}
