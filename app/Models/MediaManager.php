<?php

namespace App\Models;

use App\HelperClasses\MyApp;
use App\HelperClasses\StorageFiles;
use App\Http\Requests\BaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class MediaManager extends BaseModel
{
    use HasFactory;

    protected $with = ["userCreatedBy"];


    public const NAME_FOLDER = "uploads";

    protected $fillable = [
        "user_id","file_name","file_size","extension",
        "object_name","pdf_path","type",
        "is_active","created_by","updated_by",
    ];

    // Add relationships between tables section
    public function user(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function files_users(){
        return $this->hasMany(UserFile::class,"id_file","id");
    }

    public function files_groups(){
        return $this->hasMany(GroupFile::class,"id_file","id");
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
            $rules = $validator->fileRule(true);
            $file = $validator->file;
            if (is_file($file) || $file instanceof UploadedFile){
                if (in_array($file->getClientOriginalExtension(),MyApp::Classes()->storageFiles->getExImages(true))){
                    $rules = $validator->imageRule(true);
                }
            }else{
                $rules = $validator->fileRule(true);
            }
            return [
                //validation rule any image $validator->imageRule(false)
                //validation rule any word_name(only char and number) $validator->textRule(false)
                "file" => $rules,
                "name" => $validator->textRule(false),
            ];
        };
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): mixed
    {
        return $this->resolveRoutes('media-manager');
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
