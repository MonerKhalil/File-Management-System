<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Http\Repositories\Interfaces\IGroupUserRepository;
use App\Http\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\UJoinGroupPublicRequest;
use App\Http\Requests\USendJoinGroupPrivateRequest;

class URequestsController extends Controller
{
    private $user;
    private IGroupRepository $IGroupRepository;
    private IGroupUserRepository $IGroupUserRepository;
    private IUserRepository $IUserRepository;


    public function __construct(IGroupRepository $IGroupRepository,IGroupUserRepository $IGroupUserRepository){
        $this->user = MyApp::Classes()->getUser();
        $this->IGroupRepository = $IGroupRepository;
        $this->IGroupUserRepository = $IGroupUserRepository;
    }

    /**
     * @param $id_group
     * @return mixed
     */
    public function showRequestJoinGroup($id_group){
        $group = $this->IGroupRepository->find($id_group);
        $group->canAccessGroup();
        $requests = $this->IGroupUserRepository->get(false,function ($q)use($id_group){
            if (is_array(request()->input("filter")) && isset(request()->input("filter")['username']) && !is_null(request()->input("filter")['username'])){
                $q = $q->whereHas("user",function ($q){
                    return $q->where("name","LIKE",request()->input("filter")['username']."%");
                });
            }
            return $q->with("user")->where("is_request",1)->where("type","request_user")->where("id_group",$id_group);
        });
        return $this->responseSuccess(null,compact("requests"));
    }

    /**
     * @return mixed
     */
    public function showRequestInvitationReceived(){
        $requests = $this->IGroupUserRepository->get(false,function ($q){
            if (is_array(request()->input("filter")) && isset(request()->input("filter")['groupname']) && !is_null(request()->input("filter")['groupname'])){
                $q = $q->whereHas("group",function ($q){
                    return $q->where("name","LIKE",request()->input("filter")['groupname']."%");
                });
            }
            return $q->with("group")->where("is_request",1)->where("type","request_group")->where("id_user",$this->user->id);
        });
        return $this->responseSuccess(null,compact("requests"));
    }

    /**
     * @param USendJoinGroupPrivateRequest $request
     * @return mixed
     * @throws MainException
     */
    public function sendRequestJoinToGroupPrivate(USendJoinGroupPrivateRequest $request){
        $request_user = $this->IGroupUserRepository->create([
            "id_user" => $this->user->id,
            "id_group" => $request->id_group,
            "is_request" => 1,
            "type" => "request_user",
            "status" => "pending",
        ]);
        return $this->responseSuccess(null,compact("request_user"));
    }

    /**
     * @param UJoinGroupPublicRequest $request
     * @return mixed
     * @throws MainException
     */
    public function joinToGroupPublic(UJoinGroupPublicRequest $request){
        $this->IGroupUserRepository->create([
            "id_user" => $this->user->id,
            "id_group" => $request->id_group,
        ]);
        return $this->responseSuccess();
    }

}
