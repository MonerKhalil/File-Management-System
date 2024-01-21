<?php

namespace App\Models;

use App\HelperClasses\MyApp;
use App\Http\Requests\BaseRequest;
use App\Rules\TextRule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Language extends BaseModel
{
    use HasFactory;

    protected $with = [];


    protected $fillable = [
        "code","name","default","isRTL","isFront","isBack","png_icon",
        "is_active","created_by","updated_by","notes",
    ];

    // Add relationships between tables section

    /**
     * @description add validation in frontend
     * @return mixed
     */
    public function viewFieldsValidationFrontEnd(): mixed{
        return [
            //
            'code' => ['select' => array_flip(MyApp::Classes()->languageProcess->getAllCodeLangs())],
            'name' => 'text|required',
            'default' => ['select' => [0,1]],
            'isRTL' => ['select' => [0,1]],
            'isFront' => ['select' => [0,1]],
            'isBack' => ['select' => [0,1]],
            'png_icon' => 'image|accept="image/png, image/gif, image/jpeg, image/jpg, image/svg"|required',
            'is_active' => ['select' => [0,1]],
            'notes' => 'editor|maxlength=10000',
            'config' => [
                'table' => (new self())->getTable(),
                'ignoreFromShow' => [
                    //
               ],
               'ignoreFromEdit' => [
                   'is_active'
               ],
               'ignoreFromCreate' => [
                   'notes','is_active'
               ],
           ]
       ];
    }

    /**
     * @description add validation in backend
     * @return mixed
     */
    public function validationBackEnd(): mixed{
        $user = MyApp::Classes()->getUser();
        return function (BaseRequest $validator) use ($user) {
            $lang = $validator->route("language");
            $RulesAll = [
                //validation rule any image $validator->imageRule(false)
                //validation rule any word_name(only char and number) $validator->textRule(false)
                'code' => ["required","string",new TextRule(),Rule::unique("languages","code")],
                'name' => $validator->textRule(true),
                'default' => ["required","boolean"],
                'isRTL' => ["required","boolean"],
                'isFront' => ["required","boolean"],
                'isBack' => ["required","boolean"],
                'png_icon' => $validator->imageRule(false),
                'notes' => "nullable|string",
            ];
            $langsCode = Rule::in(array_flip(MyApp::Classes()->languageProcess->getAllCodeLangs()));
            $RulesAll['code'] = !$validator->isUpdatedRequest() ?
                ["required","string",new TextRule(),Rule::unique("languages","code"),$langsCode]
                : ["required","string",new TextRule(),Rule::unique("languages","code")->ignore($lang->id??""),$langsCode];
            if(($this instanceof BaseTranslationModel) && $validator->isUpdatedRequest()){
                $RulesAll['local_id'] = ["required",Rule::exists("languages","id")];
            }
            return $RulesAll;
        };
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): mixed
    {
        return $this->resolveRoutes('language');
    }

    public function customAttributes():array{
        return [
        ];
    }

    public function attributesFullProcess(): array{
        return [
            self::API_KEYS => [],
        ];
    }
}
