<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\Http\Repositories\Interfaces\ISocialMediaRepository;
use App\Http\Requests\SocialMediaRequest;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    /**
     * @var \App\Http\Repositories\Interfaces\ISocialMediaRepository
     */
    public $ISocialMediaRepository;

    /**
     * @var string
     */
    private $indexPage = 'social-media.index';

    /**
     * @var string
     */
    private $indexView = 'pages.social-media.index';

    /**
     * @var string
     */
    private $createView = 'pages.social-media.create';

    /**
     * @var string
     */
    private $updateView = 'pages.social-media.update';

    /**
     * @param  \App\Http\Repositories\Interfaces\ISocialMediaRepository  $ISocialMediaRepository
     */
    public function __construct(ISocialMediaRepository $ISocialMediaRepository)
    {
        $this->ISocialMediaRepository = $ISocialMediaRepository;
        $this->addMiddlewarePermissionsToFunctions($this->ISocialMediaRepository->nameTable);
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
            $data = $this->ISocialMediaRepository->get();

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
    public function store(SocialMediaRequest $request)
    {
        try {
           $result = $this->ISocialMediaRepository->create($request->validated());

           return $this->responseSuccess(null, compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(SocialMedia $socialMedia)
    {
        try {
           $dataShow = $this->ISocialMediaRepository->find($socialMedia->id);

           return $this->responseSuccess($this->indexView, compact("dataShow"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return \Illuminate\Http\Response
     */
    public function edit(SocialMedia $socialMedia)
    {
        try {
           $data = $this->ISocialMediaRepository->find($socialMedia->id);

           return $this->responseSuccess($this->updateView, compact("data"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function update(SocialMediaRequest $request, SocialMedia $socialMedia)
    {
        try {
           $result = $this->ISocialMediaRepository->update($request->validated() ,$socialMedia->id);

           return $this->responseSuccess(null, compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SocialMedia  $socialMedia
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(SocialMedia $socialMedia)
    {
        try {
           $result = $this->ISocialMediaRepository->delete($socialMedia->id);

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
           $result = $this->ISocialMediaRepository->forceDelete($id);

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
       $result = $this->ISocialMediaRepository->active($id);

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
        $result = $this->ISocialMediaRepository->activeRow($id);

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
        $result = $this->ISocialMediaRepository->deactivateRow($id);

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
        $result = $this->ISocialMediaRepository->activeMulti($request);

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
        return $this->ISocialMediaRepository->exportXLSX($request);
    }

    /**
     * export all Records Table PDF.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function exportPDF(Request $request)
    {
        return $this->ISocialMediaRepository->exportPDF($request);
    }

    /**
     * import file Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function import(Request $request)
    {
        $result = $this->ISocialMediaRepository->import($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->ISocialMediaRepository->multiDestroy($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }
}
