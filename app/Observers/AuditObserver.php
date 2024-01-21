<?php

namespace App\Observers;

use App\HelperClasses\MyApp;
use App\Models\BaseModel;
use App\Models\User;
use App\Notifications\MainNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use OwenIt\Auditing\Models\Audit;

class AuditObserver
{
    /**
     * Handle the Audit "created" event.
     *
     * @param Audit $audit
     * @return void
     */
    public function created(Audit $audit)
    {
        $auditData = $audit->toArray();
        $user = MyApp::Classes()->getUser();
        $tableName = app($auditData["auditable_type"])->getTable();
        if (!is_null($user)){
            $model = (new $auditData["auditable_type"]);
            if ($model instanceof BaseModel && isset($model->getRoutes()["show"]) && Route::has($model->getRoutes()["show"])){
                $route = route($model->getRoutes()["show"],$auditData["auditable_id"],false);
            }else{
                $route = route("notification.audit.show",[],false);
            }
            $Data = [
                "audit_id" => $auditData['id'],
                "model_id" => $auditData['auditable_id'],
                "model_type" => $auditData["auditable_type"],
                "table_name" => $tableName,
                "user_id" => $user->id,
                "user_name" => $user->name,
                "event" => $auditData['event'],
                "old_values" => $auditData['old_values'],
                "new_values" => $auditData['new_values'],
                "date" => now(),
                "route_name" => $route,
            ];
            $users = MyApp::Classes()->getUsersForPermission("all_".$tableName);
            Notification::send($users,new MainNotification($Data,"audit"));
        }
    }


}
