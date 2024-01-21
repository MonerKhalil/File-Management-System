<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\ISettingRepository;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * @var \App\Http\Repositories\Interfaces\ISettingRepository
     */
    public $ISettingRepository;

    /**
     * @var string
     */
    private $indexView = 'pages.globalSetting.company.index';

    /**
     * @param  \App\Http\Repositories\Interfaces\ISettingRepository  $ISettingRepository
     */
    public function __construct(ISettingRepository $ISettingRepository)
    {
        $this->ISettingRepository = $ISettingRepository;
//        $this->middleware(["permission:all_".$this->ISettingRepository->nameTable])->only(["updateSettings","showSettings"]);
    }

    public function showSettings(){
        $settings = $this->ISettingRepository->get(true);

        return $this->responseSuccess($this->indexView, compact("settings"));
    }

    public function updateSettings(SettingRequest $request){
        $data = $request->validated();
        if (isset($data['local_id'])){
            unset($data['local_id']);
        }
        foreach ($data as $key => $item){
            $temp = $this->ISettingRepository->find($key,null,"key",true);
            $data = MyApp::Classes()->languageProcess
                ->updateInTranslationTableSettings($temp,["value"=>$item,"local_id" => $request->local_id],$temp->is_translation ?? false);
            if (sizeof($data) > 0){
                $temp->update($data);
            }
        }
        return $this->responseSuccess(null,null,"update",null,true);
    }
}
