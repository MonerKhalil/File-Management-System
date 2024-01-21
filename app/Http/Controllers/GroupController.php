<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Exceptions\MainException;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * @var \App\Http\Repositories\Interfaces\IGroupRepository
     */
    public $IGroupRepository;

    /**
     * @var string
     */
    private $indexPage = 'group.index';

    /**
     * @var string
     */
    private $indexView = 'pages.group.index';

    /**
     * @var string
     */
    private $createView = 'pages.group.create';

    /**
     * @var string
     */
    private $updateView = 'pages.group.update';

    /**
     * @param  \App\Http\Repositories\Interfaces\IGroupRepository  $IGroupRepository
     */
    public function __construct(IGroupRepository $IGroupRepository)
    {
        $this->IGroupRepository = $IGroupRepository;
        $this->addMiddlewarePermissionsToFunctions($this->IGroupRepository->nameTable);
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
            $data = $this->IGroupRepository->get();

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
    public function store(GroupRequest $request)
    {
        try {
           $result = $this->IGroupRepository->create($request->validated());

           return $this->responseSuccess(null, compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(Group $group)
    {
        try {
           $dataShow = $this->IGroupRepository->find($group->id);

           return $this->responseSuccess($this->indexView, compact("dataShow"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        try {
           $data = $this->IGroupRepository->find($group->id);

           return $this->responseSuccess($this->updateView, compact("data"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function update(GroupRequest $request, Group $group)
    {
        try {
           $result = $this->IGroupRepository->update($request->validated() ,$group->id);

           return $this->responseSuccess(null, compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(Group $group)
    {
        try {
           $result = $this->IGroupRepository->delete($group->id);

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
           $result = $this->IGroupRepository->forceDelete($id);

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
       $result = $this->IGroupRepository->active($id);

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
        $result = $this->IGroupRepository->activeRow($id);

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
        $result = $this->IGroupRepository->deactivateRow($id);

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
        $result = $this->IGroupRepository->activeMulti($request);

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
        return $this->IGroupRepository->exportXLSX($request);
    }

    /**
     * export all Records Table PDF.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function exportPDF(Request $request)
    {
        return $this->IGroupRepository->exportPDF($request);
    }

    /**
     * import file Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function import(Request $request)
    {
        $result = $this->IGroupRepository->import($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->IGroupRepository->multiDestroy($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }
}
