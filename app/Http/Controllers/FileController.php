<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Repositories\Interfaces\IFileRepository;
use App\Exceptions\MainException;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * @var \App\Http\Repositories\Interfaces\IFileRepository
     */
    public $IFileRepository;

    /**
     * @var string
     */
    private $indexPage = 'file.index';

    /**
     * @var string
     */
    private $indexView = 'pages.file.index';

    /**
     * @var string
     */
    private $createView = 'pages.file.create';

    /**
     * @var string
     */
    private $updateView = 'pages.file.update';

    /**
     * @param  \App\Http\Repositories\Interfaces\IFileRepository  $IFileRepository
     */
    public function __construct(IFileRepository $IFileRepository)
    {
        $this->IFileRepository = $IFileRepository;
        $this->addMiddlewarePermissionsToFunctions($this->IFileRepository->nameTable);
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
            $data = $this->IFileRepository->get();

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
    public function store(FileRequest $request)
    {
        try {
           $result = $this->IFileRepository->create($request->validated());

           return $this->responseSuccess(null, compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(File $file)
    {
        try {
           $dataShow = $this->IFileRepository->find($file->id);

           return $this->responseSuccess($this->indexView, compact("dataShow"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        try {
           $data = $this->IFileRepository->find($file->id);

           return $this->responseSuccess($this->updateView, compact("data"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function update(FileRequest $request, File $file)
    {
        try {
           $result = $this->IFileRepository->update($request->validated() ,$file->id);

           return $this->responseSuccess(null, compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(File $file)
    {
        try {
           $result = $this->IFileRepository->delete($file->id);

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
           $result = $this->IFileRepository->forceDelete($id);

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
       $result = $this->IFileRepository->active($id);

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
        $result = $this->IFileRepository->activeRow($id);

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
        $result = $this->IFileRepository->deactivateRow($id);

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
        $result = $this->IFileRepository->activeMulti($request);

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
        return $this->IFileRepository->exportXLSX($request);
    }

    /**
     * export all Records Table PDF.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function exportPDF(Request $request)
    {
        return $this->IFileRepository->exportPDF($request);
    }

    /**
     * import file Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function import(Request $request)
    {
        $result = $this->IFileRepository->import($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->IFileRepository->multiDestroy($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }
}
