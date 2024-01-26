<?php

namespace App\Services;

class ChangeStatusRequestService
{
    public function mainProcessChangeStatus($request,$query){
        if ($request->status != "pending"){
            switch ($request->status){
                case "approve" :
                    $query->update([
                        "is_request" => 0,
                        "type_request" => "none",
                        "status" => "approve",
                    ]);
                    break;
                case "reject" :
                    $query->forceDelete();
                    break;
            }
        }
    }
}
