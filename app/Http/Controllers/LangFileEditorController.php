<?php

namespace App\Http\Controllers;

use App\HelperClasses\MessagesFlash;
use App\HelperClasses\MyApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;

class LangFileEditorController extends Controller
{
    private $nameFiles = [],$lang = null;

    public function __construct()
    {
        $this->nameFiles = [
            "messages","website"
        ];
    }

    public function showBladeEditor(){
        $this->language();
        $files = $this->nameFiles;
        $finalData = [];
        foreach ($files as $file){
            $path = $this->mainFolderPath($this->lang.'/'.$file.'.php');
            if (file_exists($path)){
                $finalData[$file] = require Lang::get($path);
            }
        }
        return $this->responseSuccess('pages.languageFiles.index', compact("finalData"));
    }

    public function mainProcessEdit(Request $request){
        $this->language();
        $request->validate([
            "main_data" => [
                "required" , "array",
            ],
            "main_data.*.name_file" => ["required",Rule::in($this->nameFiles),],
            "main_data.*.content_file" => ["required","array",],
        ]);
        if (!is_null($request->main_data)){
            foreach ($request->main_data as $datum){
                $this->addKeysToAllFiles($datum['content_file'],$datum['name_file']);
            }
        }
        return $this->responseSuccess(null, null, "", null,true);
    }

    public function deleteKeys(Request $request){
        $this->language();
        $request->validate([
            "key" => ["required",],
            "name_file" => ["required",Rule::in($this->nameFiles)],
        ]);
        $langs = MyApp::Classes()->languageProcess->allLangs;
        foreach ($langs as $lang){
            $path = $this->mainFolderPath($lang->code . "/" . $request->name_file . ".php");
            if (file_exists($path)){
                $data = require $path;
                if (isset($data[$request->key])){
                    unset($data[$request->key]);
                }
                $this->save($data,$request->name_file,$lang->code);
            }
        }
        return $this->responseSuccess(null,null,__(MessagesFlash::Messages("")));
    }

    private function save(array $values ,$nameFile,$lang)
    {
        $path = $this->mainFolderPath($lang . "/" . $nameFile . ".php");

        $content = "<?php\n\nreturn\n[\n";

        foreach ($values as $key => $value)
        {
            $content .= "\t'". $key ."' => '". $value ."',\n";
        }

        $content .= "];";

        if (file_exists($path)){
            file_put_contents($path, $content);
        }
    }

    private function addKeysToAllFiles(array $values ,$nameFile){
        if (isset($values["XXXX"])){
            unset($values["XXXX"]);
        }
        $langs = MyApp::Classes()->languageProcess->allLangs;
        $dataFinal = [];
        $allKeys = [];
        $files = [];
        foreach ($langs as $lang){
            $dir = $this->mainFolderPath($lang->code);
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            $path = $this->mainFolderPath($lang->code . "/" . $nameFile . ".php");
            if (!file_exists($path)){
                $FilePath = base_path("stubs/lang_file.stub");
                $File = File::get($FilePath);
                File::put($path, $File);
            }
            $files[$lang->code] = $path;
            $dataFinal[$lang->code] = [];
            $data = require $path;
            foreach ($data as $key => $value){
                $dataFinal[$lang->code][$key] = $value;
                if (!isset($allKeys[$key])){
                    $allKeys[$key] = null;
                }
            }
        }
        foreach ($values as $key => $value){
            foreach ($files as $lang => $file){
                if ($lang == $this->lang){
                    $dataFinal[$lang][$key] = $value;
                }elseif (!isset($dataFinal[$lang][$key]) && $lang != $this->lang){
                    $dataFinal[$lang][$key] = $key;
                }
            }
            if (!isset($allKeys[$key])){
                $allKeys[$key] = null;
            }
        }

        foreach ($files as $lang => $file){
            foreach ($allKeys as $key => $value){
                if (!isset($dataFinal[$lang][$key])){
                    $dataFinal[$lang][$key] = $key;
                }
            }
            $this->save($dataFinal[$lang],$nameFile,$lang);
        }
    }

    private function mainFolderPath($path = ''){
        return lang_path($path);
    }

    private function language(){
        $this->lang = app()->getLocale();
    }

    public function getFileFront(Request $request){
        $file = $request->name_file ?? "main";
        $file .= ".json";
        $path = lang_path(app()->getLocale() . "\\FrontEnd\\".$file);
        $file = file_exists($path) ? file_get_contents($path) : null;
        return $this->responseSuccess(null,compact("file"));
    }
}
