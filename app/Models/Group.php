<?php

namespace App\Models;

use App\Exceptions\MainException;
use App\Http\Repositories\Eloquent\UserRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use App\HelperClasses\MyApp;

class Group extends BaseModel
{
    use HasFactory;

    protected $with = ["user"];

    const TYPE = ["public","private"];

    protected $fillable = [
        "id_user","name","type",
        "is_active","created_by","updated_by","notes",
    ];

    // Add relationships between tables section
    public function user(){
        return $this->belongsTo(User::class,"id_user","id");
    }

    public function pivot_users(){
        return $this->hasMany(GroupUser::class,"id_group","id");
    }

    public function users(){
        return $this->belongsToMany(User::class,"group_users",
            "id_group",
            "id_user",
            "id",
            "id"
        )->withTimestamps();
    }

    public function files(){
        return $this->belongsToMany(File::class,"group_files",
            "id_group",
            "id_file",
            "id",
            "id"
        )->withTimestamps();
    }

    public function isPublic(): bool{
        return $this->type === "public";
    }

    /**
     * @return void
     * @throws MainException
     */
    public function canEditOrDelete(){
        $user = MyApp::Classes()->getUser();
        $check = ($user->id == $this->id_user) || ($user->role == Role::SUPER_ADMIN);
        if (!$check){
            throw new MainException(__("can not execute process because is not owner group -_- .."));
        }
    }

    /**
     * @description can show group or files or members
     * @return void
     * @throws MainException
     */
    public function canAccessGroup()
    {
        $user = MyApp::Classes()->getUser();
        $check = ($user->id == $this->id_user) || ($user->role == Role::SUPER_ADMIN)
            || ($this->pivot_users()->where("id_user",$user->id)->exists());
        if (!$check){
            throw new MainException(__("can not access to group -_- .."));
        }
    }

    /**
     * @description add validation in frontend
     * @return mixed
     */
    public function viewFieldsValidationFrontEnd(): mixed{
        return [
            //
            'id_user' => [
                'relation' => [
                    'model' => UserRepository::class,
                    'relationFunc' => 'user',
                    'key' => 'id',
                    'value' => 'name',
                    'validation' => 'required',
                ]
            ],
            'name' => 'text|required|maxlength=256',
            'type' => ['required','select' => self::TYPE],
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
                'id_user' => ['required',Rule::exists("users","id"),"integer"],
                'name' => $validator->textRule(true),
                'type' => ["required",Rule::in(self::TYPE)],
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
        return $this->resolveRoutes('group');
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
