<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XSS
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
        $newRequestData = $request->all();
        foreach($newRequestData as $key => $value){
            if(is_string($value)){
                $newRequestData[$key] = xss($value);
            }
        }
        $request->merge($newRequestData);
        return $next($request);
    }
}
