<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MessagesFlash;
use App\HelperClasses\MyApp;
use App\Http\Controllers\Controller;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Http\Repositories\Interfaces\IMediaManagerRepository;
use App\Http\Requests\AddFileToMeRequest;
use App\Http\Requests\MediaManagerRequest;
use App\Http\Requests\ShareFilesToGroupRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UFileController extends Controller
{
    public function __construct(private IMediaManagerRepository $IMediaManagerRepository,
                                private IGroupRepository $IGroupRepository)
    {
    }

    public function showAllFiles(){
        $files = $this->IMediaManagerRepository->get(false,function ($q){
            return $q->where(function ($q){
                return $q->orWhere("user_id",MyApp::Classes()->getUser()->id)
                    ->whereHas("files_users",function ($q){
                        return $q->where("id_user",MyApp::Classes()->getUser()->id);
                    });
            });
        });
        return $this->responseSuccess(null,compact("files"));
    }

    public function uploadFile(MediaManagerRequest $request)
    {
        $file = $request->file('file');
        $file = $this->IMediaManagerRepository->storeInMediaManager($file,true);
        return $this->responseSuccess(null, compact("file"));
    }

    public function shareFilesToGroup(ShareFilesToGroupRequest $request,$id_group){
        $group = $this->IGroupRepository->find($id_group);
        $group->canAccessGroup();
        $file_ids = $this->IMediaManagerRepository->queryModelActive()
            ->whereIn("id",$request->file_ids)
            ->where(function ($q){
                return $q->orWhere("user_id",MyApp::Classes()->getUser()->id)
                    ->whereHas("files_users",function ($q){
                        return $q->where("id_user",MyApp::Classes()->getUser()->id);
                    });
            })->select(["id"])->get()->pluck("id");
        if (sizeof($file_ids) > 0){
            $group->files()->syncWithoutDetaching($file_ids);
        }
        return $this->responseSuccess(null,null,__(MessagesFlash::Messages("default")));
    }

    public function addFilesToMe(AddFileToMeRequest $request){
        $group = $this->IGroupRepository->find($request->group_id);
        $group->canAccessGroup();
        $files = $this->IMediaManagerRepository->queryModel()
            ->whereHas("files_groups",function ($q)use($group,$request){
                return $q->where("id_group",$group->id)->whereIn("id_file",$request->file_ids);
            })
            ->select("id")
            ->get()->pluck("id");
        if (sizeof($files) > 0){
            $user = MyApp::Classes()->getUser();
            $user->userFiles()->syncWithoutDetaching($files);
        }
        return $this->responseSuccess(null,null,__(MessagesFlash::Messages("default")));
    }

    /**
     * @throws MainException
     */
    public function downloadFile($file_id){
        $user = MyApp::Classes()->getUser();
        $file = $this->IMediaManagerRepository->queryModel()->select('media_managers.*')
            ->leftJoin('user_files', 'user_files.id_file', '=', 'media_managers.id')
            ->leftJoin('group_files', 'group_files.id_file', '=', 'media_managers.id')
            ->where('media_managers.id', $file_id)
            ->where(function ($query) use($user){
                $query->where('media_managers.user_id', $user->id)
                    ->orWhere('user_files.id_user', $user->id)
                    ->orWhere(function ($query) use ($user){
                        $query->whereExists(function ($query) use ($user){
                            $query->select('group_users.id_user')
                                ->from('group_users')
                                ->join('groups', 'group_users.id_group', '=', 'groups.id')
                                ->where(function ($query) use ($user){
                                    return $query->where('group_users.id_user', $user->id)
                                        ->where('group_files.id_group', '=', 'group_users.id_group');
                                })->orWhere('groups.id_user', $user->id);
                        });
                    })->orWhere("users.role",Role::SUPER_ADMIN);
            })->first();
        if (!is_null($file)){
            return MyApp::Classes()->storageFiles->downloadFile($file->pdf_path);
        }
        throw new MainException("no permission download file or not exist id file");
    }

}
