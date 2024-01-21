<?php

namespace App\Models;

use App\HelperClasses\MyApp;
use App\Http\Requests\BaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Setting extends BaseTranslationModel
{
    use HasFactory;

    protected $with = ['translation',];


    protected $fillable = [
        "key","value","type","is_translation","is_required",
        "is_active","created_by","updated_by"
    ];

	/**
	 * @return array
	 */
	public function attributesTranslations(): array{
		return ["value"];
	}

	// Add relationships between tables section
    public function translation(){
        return $this->hasMany(SettingTranslation::class,"main_id","id");
    }


    /**
     * @description add validation in frontend
     * @return mixed
     */
    public function viewFieldsValidationFrontEnd(): mixed{
        return [
            //

            'is_active' => ['select' => [0,1]],
            'config' => [
                'table' => (new self())->getTable(),
                'ignoreFromShow' => [
                    //
               ],
               'ignoreFromEdit' => [
                   'is_active'
               ],
               'ignoreFromCreate' => [
                   'is_active'
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
            $RulesAll = $validator->validationKeysSettings();
            if($this instanceof BaseTranslationModel){
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
        return $this->resolveRoutes('setting');
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
