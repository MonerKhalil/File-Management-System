<?php

namespace App\Http\Controllers\AuthApi;

use App\HelperClasses\MyApp;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\BaseRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function user(){
        $user = MyApp::Classes()->getUser();
        return $this->responseSuccess(null,[
            "user" => $user,
        ]);
    }

    public function register(Request $request){
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', BaseRequest::validationPassword()],
        ]);

        $user = User::create([
            'name' => $request->first_name . " " . $request->last_name,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($user->name,["*"])->plainTextToken;

        return $this->responseSuccess(null,[
            "user" => $user,
            "token" => $token,
        ]);
    }

    public function login(LoginRequest $request){
        $user = User::query()->where("email",$request->email)->first();
        if (is_null($user)){
            throw ValidationException::withMessages([
                'email/password' => __('auth.failed'),
            ]);
        }
        if (password_verify($request->password,$user->password)){
            $token = $user->createToken($user->name,["*"])->plainTextToken;
            return $this->responseSuccess(null,[
                "user" => $user,
                "token" => $token,
            ]);
        }
        else{
            throw ValidationException::withMessages([
                'email/password' => __('auth.failed'),
            ]);
        }
    }

    public function logout(){
        $user = MyApp::Classes()->getUser();
        $user->tokens()->delete();
        return $this->responseSuccess(null,null,"Successfully logged out");
    }
}
