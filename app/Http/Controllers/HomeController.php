<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Interfaces\IFileRepository;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Http\Repositories\Interfaces\IUserRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    private IUserRepository $IUserRepository;
    private IGroupRepository $IGroupRepository;
    private IFileRepository $IFileRepository;

    public function __construct(IUserRepository $IUserRepository,IGroupRepository $IGroupRepository,IFileRepository $IFileRepository)
    {
        $this->IFileRepository = $IFileRepository;
        $this->IGroupRepository = $IGroupRepository;
        $this->IUserRepository = $IUserRepository;
    }

    public function dashboard(){
        $countUsers = $this->IUserRepository->queryModelWithActive()->where("role","user")->count();
        $countAdmins = $this->IUserRepository->queryModelWithActive()->where("role","super_admin")->count();
        $countGroupsPrivate = $this->IGroupRepository->queryModelWithActive()->where("type","private")->count();
        $countGroupsPublic = $this->IGroupRepository->queryModelWithActive()->where("type","public")->count();
        $countFiles = $this->IFileRepository->queryModelWithActive()->whereHas("file",function ($q){
            return $q->where("type","file");
        })->count();
        $countImages = $this->IFileRepository->queryModelWithActive()->whereHas("file",function ($q){
            return $q->where("type","image");
        })->count();
        return $this->responseSuccess(null,compact("countAdmins","countUsers",
            "countGroupsPrivate","countGroupsPublic","countImages","countFiles"));
    }
}
