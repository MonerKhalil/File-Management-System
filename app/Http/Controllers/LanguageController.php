<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\Http\Repositories\Interfaces\ILanguageRepository;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * @var \App\Http\Repositories\Interfaces\ILanguageRepository
     */
    public $ILanguageRepository;

    /**
     * @var string
     */
    private $indexPage = 'language.index';

    /**
     * @var string
     */
    private $indexView = 'pages.language.index';

    /**
     * @var string
     */
    private $createView = 'pages.language.create';

    /**
     * @var string
     */
    private $updateView = 'pages.language.update';

    /**
     * @param  \App\Http\Repositories\Interfaces\ILanguageRepository  $ILanguageRepository
     */
    public function __construct(ILanguageRepository $ILanguageRepository)
    {
        $this->ILanguageRepository = $ILanguageRepository;
        $this->addMiddlewarePermissionsToFunctions($this->ILanguageRepository->nameTable);
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
            $data = $this->ILanguageRepository->get();

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
    public function store(LanguageRequest $request)
    {
        try {
            $default = $request->default;
            if ($default){
                $this->ILanguageRepository->queryModel()->update([
                    "default" => 0,
                ]);
            }
           $result = $this->ILanguageRepository->create($request->validated());

           return $this->responseSuccess(null, compact("result"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function show(Language $language)
    {
        try {
           $dataShow = $this->ILanguageRepository->find($language->id);

           return $this->responseSuccess($this->indexView, compact("dataShow"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function edit(Language $language)
    {
        try {
           $data = $this->ILanguageRepository->find($language->id);

           return $this->responseSuccess($this->updateView, compact("data"));

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function update(LanguageRequest $request, Language $language)
    {
        try {
            $default = $request->default;
            if ($default){
                $this->ILanguageRepository->queryModel()->update([
                    "default" => 0,
                ]);
                $temp = $this->ILanguageRepository->update($request->validated() ,$language->id);
            }else{
                if ($language->default){
                    $temp = $this->ILanguageRepository->queryModelWithActive()
                        ->where("default",1)->whereNot("id",$language->id)->first();
                    if (!is_null($temp)){
                        $this->ILanguageRepository->update($request->validated() ,$language->id);
                    }
                    $temp = null;
                }else{
                    $temp = $this->ILanguageRepository->update($request->validated() ,$language->id);
                }
            }

           return $this->responseSuccess(null, compact("temp"), null, $this->indexPage);

        } catch (\Exception $e) {
          throw new MainException($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function destroy(Language $language)
    {
        try {
            if ($language->default){
                return $this->responseError("the language is default you cant deleted.",MainException::class);
            }
           $result = $this->ILanguageRepository->delete($language->id);

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
            $language = $this->ILanguageRepository->find($id);
            if ($language->default){
                return $this->responseError("the language is default you cant deleted.",MainException::class);
            }
           $result = $this->ILanguageRepository->forceDelete($id);

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
       $result = $this->ILanguageRepository->active($id);

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
        $result = $this->ILanguageRepository->activeRow($id);

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
        $result = $this->ILanguageRepository->deactivateRow($id);

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
        $result = $this->ILanguageRepository->activeMulti($request);

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
        return $this->ILanguageRepository->exportXLSX($request);
    }

    /**
     * export all Records Table PDF.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function exportPDF(Request $request)
    {
        return $this->ILanguageRepository->exportPDF($request);
    }

    /**
     * import file Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function import(Request $request)
    {
        $result = $this->ILanguageRepository->import($request);

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    /**
     * delete multi ids Records Table.
     *
     * @return \Illuminate\Http\Response
     * @author moner khalil
     */
    public function multiDestroy(Request $request){
        $result = $this->ILanguageRepository->multiDestroy($request,true,function ($q){
            return $q->where("default",0);
        });

        return $this->responseSuccess(null, null, null, $this->indexPage);
    }

    public function getAllLanguges(){
        $languges = $this->ILanguageRepository->get(true);

        return $this->responseSuccess($this->indexView, compact("languges"));
    }
}
