<?php

namespace App\Models;

use App\HelperClasses\MyApp;
use App\Http\Requests\BaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class SocialMedia extends BaseModel
{
    use HasFactory;

    protected $with = [];


    protected $fillable = [
        "name" ,"url","icon_png",
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
            'name' => 'text|required|maxlength=255',
            'url' => 'url|required|maxlength=255',
            'icon_png' => 'image|accept="image/png, image/gif, image/jpeg, image/jpg, image/svg, image/icon"|required',
            'is_active' => ['select' => [0,1]],
            'notes' => 'editor|max:10000',
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
            $RulesAll = [
                //validation rule any image $validator->imageRule(false)
                //validation rule any word_name(only char and number) $validator->textRule(false)
                "name" => $validator->textRule(true),
                "url" => ["required","url"],
                "icon_png" => $validator->imageRule(true,true),
                'notes' => "nullable|string",
            ];
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
        return $this->resolveRoutes('social-media');
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
