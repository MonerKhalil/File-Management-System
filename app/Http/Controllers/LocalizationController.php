<?php

namespace App\Http\Controllers;

use App\HelperClasses\MyApp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LocalizationController extends Controller
{
    /**
     * @param $Lang
     * @return RedirectResponse
     * @author moner khalil
     */
    public function SwitchLang($Lang): RedirectResponse
    {
        $locale = MyApp::Classes()->languageProcess->getDefaultLang($Lang);
        App::setLocale($locale->code);
        Session::put(MyApp::Classes()->localeSessionKey,$locale->code);
        return redirect()->back();
    }
}
