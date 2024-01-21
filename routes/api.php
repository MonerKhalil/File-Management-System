<?php

use App\HelperClasses\MyApp;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\AuthApi\AuthController;
use App\Http\Controllers\AuthApi\PasswordResetForgetController;
use App\Http\Controllers\AuthApi\VerifyEmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MediaManagerController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SettingControlController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UGroupController;
use App\Http\Controllers\URequestsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post("register",[AuthController::class,"register"]);
Route::post("login",[AuthController::class,"login"]);
Route::post('forgot-password', [PasswordResetForgetController::class, 'forgotPasswordSendEmail']);
Route::post('reset-password', [PasswordResetForgetController::class, 'resetPassword']);

Route::get("languages/all",[LanguageController::class,"getAllLanguges"]);

Route::middleware(["userCheckAuth","throttle:31,1","xss",])->group(function (){
    Route::post('send/verify-email', [VerifyEmailController::class, 'sendEmailVerify']);
    Route::post('verify-email', [VerifyEmailController::class, 'emailVerify']);
});

Route::middleware(["userCheckAuth","throttle:31,1","xss","isActive","isVerify"])->group(function (){
    Route::prefix("auth")->group(function (){
        Route::get('user',[AuthController::class,"user"]);
        Route::post('password-update', [PasswordController::class, 'update']);
        Route::delete('logout', [AuthController::class, 'logout']);
    });


    Route::prefix("notification")->name("notification.")->group(function () {
        Route::get("show/all",[NotificationController::class,"getNotifications"]);
        Route::get("show/all/with-out-audit",[NotificationController::class,"getAllNotificationsWithOutAudit"]);
        Route::get("audit/show", [NotificationController::class, "AllNotificationsAuditUserShow"]);
        Route::get("print/table/show", [NotificationController::class, "AllNotificationsPrintTable"]);
        Route::get("log/error/show", [NotificationController::class, "errorLog"]);
        Route::post("edit/notifications/read", [NotificationController::class, "editNotificationsToRead"]);
    });

    Route::prefix("profile")->controller(UserController::class)->group(function (){
        Route::get("show","showProfileUser");
        Route::post("edit","editProfileUser");
    });

    Route::middleware(["role:super_admin"])->group(function (){
        Route::get("dashboard",[HomeController::class,"dashboard"]);

        MyApp::Classes()->fetchCrudGeneratorRoutes(true);

        Route::prefix("setting")->name("setting.")
            ->controller(SettingController::class)
            ->group(function (){
                Route::get("show","showSettings");
                Route::put("edit","updateSettings");
            });
        Route::prefix("setting-control")->name("setting.control.")
            ->controller(SettingControlController::class)
            ->group(function (){
                Route::get("show","showSettings");
                Route::put("edit","updateSettings");
            });
        Route::prefix("media-manager")
            ->controller(MediaManagerController::class)
            ->group(function (){
                Route::get("all","index");
                Route::post("store","store");
                Route::delete("destroy","destroy");
            });
        Route::post("users/reset/password", [UserController::class, "resetPassword"]);
    });

    Route::prefix("groups")->group(function(){
        //Group
        Route::controller(UGroupController::class)->group(function (){
            Route::get("all","showGroupsAll");
            Route::get("creator","showMyGroups");
            Route::get("join","showGroupsJoin");
            Route::get("show/details/all/{id_group}","showGroup");
            Route::get("show/details/{id_group}/users","showGroupDetailUsers");
            Route::get("show/details/{id_group}/files","showGroupDetailFiles");
            Route::post("add","addGroup");
            Route::put("edit/{id_group}","editGroup");
            Route::delete("destroy/{id_group}","deleteGroup");
        });
        //Requests
        Route::controller(URequestsController::class)->group(function (){
            //owner-group
            Route::get("show/all/requests/group/{id_group}/join","showRequestJoinGroup");
            Route::post("request/join/change/status/{type}","");
            Route::post("send/request/join/users","");
            //user
            Route::get("show/all/my/request-invitation/received","showRequestInvitationReceived");
            Route::post("send/request/join/group/private","sendRequestJoinToGroupPrivate");
            Route::post("join/group/public","joinToGroupPublic");
            Route::delete("leave/group/{id_group}","leaveGroup");

            Route::delete("destroy/request/{request}","");
        });
    });

    Route::prefix("files")->group(function (){
        Route::get("show/all",[]);
        Route::get("show/can/share",[]);
        Route::post("upload",[]);
        Route::put("edit/name/{file}",[]);
        Route::delete("file/{file}",[]);
        Route::post("share/group/{id_group}",[]);
        Route::post("add/file/to/user",[]);
//        Route::get("download",function (Request $request){
//            return MyApp::Classes()->storageFiles->downloadFile($request->path);
//        });
    });
});



