<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MessagesFlash;
use App\Http\Repositories\Interfaces\IMediaManagerRepository;
use App\Http\Requests\MediaManagerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MediaManagerController extends Controller
{
    /**
     * @var \App\Http\Repositories\Interfaces\IMediaManagerRepository
     */
    public $IMediaManagerRepository;

    /**
     * @var string
     */
    private $indexPage = 'media-manager.index';

    /**
     * @var string
     */
    private $indexView = 'pages.media-manager.index';

    /**
     * @var string
     */
    private $createView = 'pages.media-manager.index';

    /**
     * @param  \App\Http\Repositories\Interfaces\IMediaManagerRepository  $IMediaManagerRepository
     */
    public function __construct(IMediaManagerRepository $IMediaManagerRepository)
    {
        $this->IMediaManagerRepository = $IMediaManagerRepository;
        $this->addMiddlewarePermissionsToFunctions($this->IMediaManagerRepository->nameTable);
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
            $data = $this->IMediaManagerRepository->get();

            if (\request()->ajax()){
                return $this->responseSuccess(null, compact("data"));
            }
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
    public function store(MediaManagerRequest $request)
    {
        try {
            $file = $request->file('file');
            DB::beginTransaction();
            $item = $this->IMediaManagerRepository->storeInMediaManager($file,true);
            DB::commit();
            if (\request()->ajax()){
                return $this->responseSuccess(null, compact("item"));
            }
            return $this->responseSuccess(null, compact("item"), "create", null,true);

        } catch (\Exception $e) {
            DB::rollBack();
            throw new MainException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MediaManager  $mediaManager
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(Request $request)
    {
        $request->validate([
            "id" => ["required",Rule::exists("media_managers","id")],
        ]);
        try {
           $result = $this->IMediaManagerRepository->forceDelete($request->id);
           if (\request()->ajax()){
               return $this->responseSuccess(null, null, "delete");
           }
           return $this->responseSuccess(null, null, "delete", $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }
}
