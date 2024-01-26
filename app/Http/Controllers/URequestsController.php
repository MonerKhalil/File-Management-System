<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MessagesFlash;
use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Http\Repositories\Interfaces\IGroupUserRepository;
use App\Http\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\RChangeStatusGroupRequest;
use App\Http\Requests\RChangeStatusUserRequest;
use App\Http\Requests\SendInvitationToUserRequest;
use App\Http\Requests\UJoinGroupPublicRequest;
use App\Http\Requests\USendJoinGroupPrivateRequest;
use App\Services\ChangeStatusRequestService;
use Illuminate\Http\Request;

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

    public function changeStatusRequestUsersJoin(RChangeStatusUserRequest $request,$id_group,ChangeStatusRequestService $service){
        $group = $this->IGroupRepository->find($id_group);
        $group->canAccessGroup();
        $query = $this->IGroupUserRepository->queryModelWithActive()
            ->whereIn("id",$request->request_user_group_id)
            ->where("id_group",$id_group);
        $service->mainProcessChangeStatus($request,$query);
        return $this->responseSuccess(null,null,__(MessagesFlash::Messages("default")));
    }

    public function sendRequestJoinToUsers(SendInvitationToUserRequest $request,$id_group){
        $group = $this->IGroupRepository->find($id_group);
        $group->canAccessGroup();
        $group->users()->attach($request->user_ids,[
            "is_request" => 0,
            "type_request" => "request_group",
            "status" => "pending",
        ]);
        return $this->responseSuccess(null,null,__(MessagesFlash::Messages("default")));
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

    public function changeStatusRequestReceivedFromGroup(RChangeStatusGroupRequest $request,ChangeStatusRequestService $service){
        $query = $this->IGroupUserRepository->queryModelWithActive()
            ->where("is_request",1)
            ->where("type","request_group")
            ->where("id_user",$this->user->id)
            ->whereIn("id",$request->request_user_group_id);
        $service->mainProcessChangeStatus($request,$query);
        return $this->responseSuccess(null,null,__(MessagesFlash::Messages("default")));
    }

    public function leaveGroup($id_group){
        $this->IGroupUserRepository->queryModel()
            ->where("id_user",$this->user->id)
            ->where("id_group",$id_group)
            ->forceDelete();
        return $this->responseSuccess(null,null,__(MessagesFlash::Messages("default")));
    }

}
