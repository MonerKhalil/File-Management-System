<?php

namespace App\Models;

use App\Http\Repositories\Eloquent\MediaManagerRepository;
use App\Http\Repositories\Eloquent\UserRepository;
use App\Http\Requests\MediaManagerRequest;
use App\Rules\TextRule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use App\HelperClasses\MyApp;

class File extends BaseModel
{
    use HasFactory;

    protected $with = [];


    protected $fillable = [
        "id_user","name","id_media_manager",
        "is_active","created_by","updated_by","notes",
    ];

    protected $hidden = ['pivot'];

    // Add relationships between tables section

    public function user()
    {
        return $this->belongsTo(User::class,"id_user","id")->select(["users.id","users.name"])->withDefault();
    }

    public function file(){
        return $this->belongsTo(MediaManager::class,"id_media_manager","id");
    }

    public function userBookings(){
        return $this->belongsToMany(User::class,"user_files",
            "id_file",
            "id_user",
            "id",
            "id"
        )->withTimestamps();
    }

    public function pivot_groups(){
        return $this->hasMany(GroupFile::class,"id_file","id");
    }

    public function groups(){
        return $this->belongsToMany(Group::class,"group_files",
            "id_file",
            "id_group",
            "id",
            "id"
        )->withTimestamps();
    }

    public function CheckIsBooking(): bool
    {
        return $this->userBookings()->exists();
    }

    /**
     * @description add validation in frontend
     * @return mixed
     */
    public function viewFieldsValidationFrontEnd(): mixed{
        return [
            "id_user" => [
                "relation" => [
                    'model' => UserRepository::class,
                    'relationFunc' => 'user',
                    'key' => 'id',
                    'value' => 'name',
                    'validation' => 'required',
                ],
            ],
            "id_media_manager" => [
                "relation" => [
                    'model' => MediaManagerRepository::class,
                    'relationFunc' => 'file',
                    'key' => 'id',
                    'value' => 'file_name',
                    'validation' => 'required',
                ],
            ],
            "name" => "text|required|maxlength=256",
            "file" => "file|required",
            'is_active' => ['select' => [0,1]],
            'notes' => 'editor|max:10000',
            'config' => [
                'table' => (new self())->getTable(),
                'ignoreFromShow' => [
                    'file'
               ],
               'ignoreFromEdit' => [
                   'is_active','id_media_manager',
               ],
               'ignoreFromCreate' => [
                   'notes','is_active','id_media_manager',
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
                'id_user' => ['required',Rule::exists("users","id"),"integer"],
                'name' => $validator->isUpdatedRequest() ? ["required","string",new TextRule(),Rule::unique("files","name")->ignore($validator->route("file")?->id)]
                : ["required","string",new TextRule(),Rule::unique("files","name")] ,
                'notes' => "nullable|string",
            ];
            if(($this instanceof BaseTranslationModel) && $validator->isUpdatedRequest()){
                $RulesAll['local_id'] = ["required",Rule::exists("languages","id")];
            }
            return $RulesAll + (new MediaManagerRequest())->rules();
        };
    }

    /**
     * @inheritDoc
     */
    public function getRoutes(): mixed
    {
        return $this->resolveRoutes('file');
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
