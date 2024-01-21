<?php

namespace App\Http\Controllers;

use App\Exceptions\MainException;
use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\ISettingControlRepository;
use App\Http\Requests\SettingControlRequest;
use App\Models\SettingControl;
use Illuminate\Http\Request;

class SettingControlController extends Controller
{
    /**
     * @var \App\Http\Repositories\Interfaces\ISettingControlRepository
     */
    public $ISettingControlRepository;

    /**
     * @var string
     */
    private $indexView = 'pages.globalSetting.control.index';

    /**
     * @param  \App\Http\Repositories\Interfaces\ISettingControlRepository  $ISettingControlRepository
     */
    public function __construct(ISettingControlRepository $ISettingControlRepository)
    {
        $this->ISettingControlRepository = $ISettingControlRepository;
//        $this->middleware(["permission:all_".$this->ISettingControlRepository->nameTable])->only(["updateSettings","showSettings"]);
    }

    public function showSettings(){
        $categories = SettingControl::TYPES;
        $settings = $this->ISettingControlRepository->get(true);
        return $this->responseSuccess($this->indexView, compact("settings","categories"));
    }

    public function updateSettings(SettingControlRequest $request){
        $data = $request->validated();
        foreach ($data as $key => $value){
            $temp = $this->ISettingControlRepository->find($key,null,"key",true);
            $temp->update([
                "value" => $value,
            ]);
            updateDotEnv($key,$value);
        }
        return $this->responseSuccess(null,null,"update",null,true);
    }
}
