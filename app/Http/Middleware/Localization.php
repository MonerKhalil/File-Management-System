<?php

namespace App\Http\Middleware;

use App\HelperClasses\MyApp;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class Localization
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
        if (urlIsApi()){
            $lang = $request->lang ?? "";
            $locale = MyApp::Classes()->languageProcess->getDefaultLang($lang)->code;
        }else{
            if (is_null(Session::get(MyApp::Classes()->localeSessionKey))){
                Session::put(MyApp::Classes()->localeSessionKey,MyApp::Classes()->languageProcess->defaultLang->code);
            }
            $locale = Session::get(MyApp::Classes()->localeSessionKey);
        }
        App::setLocale($locale);
        MyApp::Classes()->languageProcess->setLangLocal($locale);
        return $next($request);
    }
}
