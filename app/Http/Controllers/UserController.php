<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\IRoleRepository;
use App\Http\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * @var IUserRepository
     */
    public $IUserRepository;

    /**
     * @var IRoleRepository
     */
    public $IRoleRepository;
    /**
     * @var string
     */
    private $indexPage = 'user.index';

    /**
     * @var string
     */
    private $indexView = 'pages.user.index';

    /**
     * @var string
     */
    private $createView = 'pages.user.create';

    /**
     * @var string
     */
    private $updateView = 'pages.user.update';

    /**
     * @param IUserRepository $IUserRepository
     */
    public function __construct(IUserRepository $IUserRepository,IRoleRepository $IRoleRepository)
    {
        $this->IUserRepository = $IUserRepository;
        $this->IRoleRepository = $IRoleRepository;
        $this->addMiddlewarePermissionsToFunctions($this->IUserRepository->nameTable);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function index()
    {
        try {
            $data = $this->IUserRepository->get();

            return $this->responseSuccess($this->indexView, compact("data"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->responseSuccess($this->createView, null);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function store(UserRequest $request)
    {
        try {
           $result = $this->IUserRepository->create($request->validated());

           return $this->responseSuccess(null,  compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(User $user)
    {
        try {
           $dataShow = $this->IUserRepository->find($user->id);

           return $this->responseSuccess($this->indexView, compact("dataShow"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        try {
            $roles = $this->IRoleRepository->all();

            $data = $this->IUserRepository->find($user->id);

           return $this->responseSuccess($this->updateView, compact("data","roles"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function update(UserRequest $request, User $user)
    {
        try {
           $result = $this->IUserRepository->update($request->validated() ,$user->id);

           return $this->responseSuccess(null,  compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(User $user)
    {
        try {
           $result = $this->IUserRepository->delete($user->id);

           return $this->responseSuccess(null, null, null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function forceDestroy($id)
    {
        try {
           $result = $this->IUserRepository->forceDelete($id);

           return $this->responseSuccess(null, null, null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * active multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function active($id)
    {
       $result = $this->IUserRepository->active($id);

       return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * active multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function activeRow($id)
    {
        $result = $this->IUserRepository->activeRow($id);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * active multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function deactivateRow($id)
    {
        $result = $this->IUserRepository->deactivateRow($id);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * active multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function activeMulti(Request $request)
    {
        $result = $this->IUserRepository->activeMulti($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * export all Records Table Xlsx.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function exportXLSX(Request $request)
    {
        return $this->IUserRepository->exportXLSX($request);
    }

    /**
     * export all Records Table PDF.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function exportPDF(Request $request)
    {
        return $this->IUserRepository->exportPDF($request);
    }

    /**
     * import file Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function import(Request $request)
    {
        $result = $this->IUserRepository->import($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->IUserRepository->multiDestroy($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    public function resetPassword(Request $request){
        $request->validate([
            "ids" => ["required","array"],
            "ids.*" => ["required",Rule::exists("users","id")],
        ]);
        $this->IUserRepository->queryModelWithActive()->whereIn("id",$request->ids)
        ->update([
            "password" => Hash::make(User::PASSWORD),
        ]);
        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    ######################### PROFILE USER #########################

    public function showProfileUser(){
        $user = MyApp::Classes()->getUser();
        return $this->responseSuccess("pages.userSetting.userInformation",compact("user"));
    }

    public function editProfileUser(UserProfileRequest $request){
        try {
            $user = MyApp::Classes()->getUser();
            $data = $request->validated();
            if ($request->email != $user->email){
                $data["email_verified_at"] = null;
            }
            $result = $this->IUserRepository->update($data ,$user->id);

            return $this->responseSuccess(null,  compact("result"), null,null,true);

        } catch (\Exception $e) {
            throw new MainException($e->getMessage());
        }
    }

}
