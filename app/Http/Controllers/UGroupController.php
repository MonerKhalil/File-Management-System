<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Http\Repositories\Interfaces\IMediaManagerRepository;
use App\Http\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\UGroupRequest;

class UGroupController extends Controller
{
    private $user;
    private IGroupRepository $IGroupRepository;
    private IMediaManagerRepository $IFileRepository;
    private IUserRepository $IUserRepository;


    public function __construct(IGroupRepository $IGroupRepository,IUserRepository $IUserRepository,
                                IMediaManagerRepository $IFileRepository){
        $this->user = MyApp::Classes()->getUser();
        $this->IGroupRepository = $IGroupRepository;
        $this->IFileRepository = $IFileRepository;
        $this->IUserRepository = $IUserRepository;
    }

    public function showGroupsAll(){
        $groups = $this->IGroupRepository->get();
        return $this->responseSuccess(null,compact("groups"));
    }

    /**
     * @return mixed
     */
    public function showMyGroups(){
        $groups = $this->IGroupRepository->get(false,function ($q){
            return $q->where("id_user",$this->user->id);
        });
        return $this->responseSuccess(null,compact("groups"));
    }

    /**
     * @return mixed
     */
    public function showGroupsJoin(){
        $groups = $this->IGroupRepository->get(false,function ($q){
            return $q->with(["user"])->whereHas("pivot_users",function ($q){
               return $q->where("id_user",$this->user->id);
            });
        });
        return $this->responseSuccess(null,compact("groups"));
    }

    /**
     * @param $id_group
     * @return mixed
     * @throws MainException
     */
    public function showGroup($id_group){
        $group = $this->IGroupRepository->find($id_group,function ($q){
            return $q->with(["users","files"]);
        });
        $group->canAccessGroup();
        return $this->responseSuccess(null,compact("group"));
    }

    /**
     * @param $id_group
     * @return mixed
     */
    public function showGroupDetailUsers($id_group){
        $group = $this->IGroupRepository->find($id_group);
        $group->canAccessGroup();
        $users = $this->IUserRepository->get(false,function ($q)use($id_group){
            return $q->whereHas("pivot_users",function ($q)use($id_group){
                return $q->where("id_group",$id_group)->where("is_request",0);
            });
        });
        return $this->responseSuccess(null,compact("group","users"));
    }

    /**
     * @param $id_group
     * @return mixed
     */
    public function showGroupDetailFiles($id_group){
        $group = $this->IGroupRepository->find($id_group);
        $group->canAccessGroup();
        $files = $this->IFileRepository->get(false,function ($q)use($id_group){
            return $q->whereHas("files_groups",function ($q)use($id_group){
                return $q->where("id_group",$id_group);
            });
        });
        return $this->responseSuccess(null,compact("group","files"));
    }

    /**
     * @param UGroupRequest $request
     * @return mixed
     * @throws MainException
     */
    public function addGroup(UGroupRequest $request){
        $group = $this->IGroupRepository->create($request->validated());
        return $this->responseSuccess(null,compact("group"));
    }

    /**
     * @param UGroupRequest $request
     * @param $id_group
     * @return mixed
     * @throws MainException
     */
    public function editGroup(UGroupRequest $request, $id_group){
        $group = $this->IGroupRepository->update($request->validated() ,$id_group);
        return $this->responseSuccess(null,compact("group"));
    }

    /**
     * @param $id_group
     * @return mixed
     * @throws MainException
     */
    public function deleteGroup($id_group){
        $this->IGroupRepository->delete($id_group);
        return $this->responseSuccess();
    }

}
