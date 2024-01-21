<?php

namespace App\Http\Controllers\AuthApi;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\Http\Controllers\Controller;
use App\Mail\MailAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Modules\EmailTemplate\Services\Mails\TokenSendMailService;

class VerifyEmailController extends Controller
{
    public function sendEmailVerify(){

        $user = MyApp::Classes()->getUser();

        if(!is_null($user->email_verified_at)){
            return $this->responseError("The email is already verified","MainException");
        }

        (new TokenSendMailService())->mainSender($user->email,[
            "titleProcess" => "Verify Email",
        ]);

        return $this->responseSuccess(null,[],"send message to email success.");
    }

    public function emailVerify(Request $request){
        $request->validate([
            'token' => ['required'],
        ]);

        $user = MyApp::Classes()->getUser();

        if (password_verify($request->token,$user->token_reset_password_api)){
            $user->update([
                "email_verified_at" => now(),
                "token_reset_password_api" => "",
            ]);
            return $this->responseSuccess(null,[],"success Verify Email.");
        }
        throw new MainException("the token is not valid.");
    }
}
