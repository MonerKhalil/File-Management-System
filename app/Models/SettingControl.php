<?php

namespace App\Models;

use App\HelperClasses\MyApp;
use App\Http\Requests\BaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class SettingControl extends BaseModel
{
    use HasFactory;

    protected $with = [];

    public const TYPES = ["business","system","general"];

    protected $fillable = [
        "key","category","value","type","is_required",
        "is_active","created_by","updated_by",
    ];

    // Add relationships between tables section

    /**
     * @description add validation in frontend
     * @return mixed
     */
    public function viewFieldsValidationFrontEnd(): mixed{
        return [
            //
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
            $settings = SettingControl::query()->get();
            return $validator->validationKeysSettings($settings);
        };
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): mixed
    {
        return $this->resolveRoutes('setting-control');
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
