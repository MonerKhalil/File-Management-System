<?php

namespace App\Http\Controllers\AuthApi;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BaseRequest;
use App\Mail\MailAuth;
use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\EmailTemplate\Services\Mails\TokenSendMailService;

class PasswordResetForgetController extends Controller
{
    public function forgotPasswordSendEmail(Request $request){
        $request->validate([
            'email' => ['required', 'email', Rule::exists("users","email")],
        ]);

        (new TokenSendMailService())->mainSender($request->email,[
            "titleProcess" => "Reset Password",
        ]);

        return $this->responseSuccess(null,[],"send message to email success.");
    }

    public function resetPassword(Request $request){
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', BaseRequest::validationPassword()],
            'confirm_password' =>['required','same:password'],
        ]);

        $user = User::query()->where("email",$request->email)->first();

        if (password_verify($request->token,$user->token_reset_password_api)){
            $user->update([
                "password" => Hash::make($request->password),
                "token_reset_password_api" => "",
            ]);
            $user->tokens()->delete();
            return $this->responseSuccess(null,[],"success update reset password.");
        }
        throw new MainException("the token is not valid.");
    }
}
