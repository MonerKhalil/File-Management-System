<?php

namespace App\Http\Middleware;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\HelperClasses\TResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsVerify
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
        if (!is_null($user) && !is_null($user->email_verified_at)) {
            return $next($request);
        }
        return $this->responseError("this user is currently not Verify","AuthenticationException"
            ,false,null,"verification.notice",500);
    }
}
