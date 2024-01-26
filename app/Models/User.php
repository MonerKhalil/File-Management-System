<?php

namespace App\Models;

use App\HelperClasses\MyApp;
use App\Http\Repositories\Eloquent\RoleRepository;
use App\Http\Requests\BaseRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\LaratrustUserTrait;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends BaseTranslationModel implements AuthenticatableContract,AuthorizableContract,CanResetPasswordContract
{
    use LaratrustUserTrait;
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    use HasApiTokens, HasFactory, Notifiable;

    public const PASSWORD = "P@ssw0rd@111@";

    protected $with = ['translation',];

    public array $API_KEYS = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','first_name','last_name',
        'png_image','role','is_active','created_by','updated_by',
        'token_reset_password_api',
        'email','email_verified_at',
        'password','token_reset_password_api'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password','pivot',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return array
     */
    public function attributesTranslations(): array{
        return ['name','first_name','last_name',];
    }

    // Add relationships between tables section
    public function pivot_users(){
        return $this->hasMany(GroupUser::class,"id_user","id")->where("is_request",0);
    }

    public function Myfiles(){
        return $this->hasMany(MediaManager::class,"id_user","id");
    }

    public function Mygroups(){
        return $this->hasMany(Group::class,"id_user","id");
    }

    public function userGroups(){
        return $this->belongsToMany(Group::class,"group_users",
            "id_user",
            "id_group",
            "id",
            "id"
        )->withTimestamps();
    }

    public function userFiles(){
        return $this->belongsToMany(MediaManager::class,"user_files",
            "id_user",
            "id_file",
            "id",
            "id"
        )->withTimestamps();
    }

    public function translation(){
        return $this->hasMany(UserTranslation::class,"main_id","id");
    }

    /**
     * @description add validation in frontend
     * @return mixed
     */
    public function viewFieldsValidationFrontEnd(): mixed{
        return [
            'role' => [
                'relation' => [
                    'model' => RoleRepository::class,
                    'relationFunc' => '...',
                    'key' => 'name',
                    'value' => 'display_name',
                    'validation' => 'required',
                ]
            ],
            'name' => 'text|required',
            'first_name' => 'text|required',
            'last_name' => 'text|required',
            'email' => 'email|required',
            'png_image' => 'image|accept="image/png, image/gif, image/jpeg, image/jpg, image/svg"',
            'is_active' => ['select' => [0,1]],
            'config' => [
                'table' => (new self())->getTable(),
                'ignoreFromShow' => [
                    //
                ],
                'ignoreFromEdit' => [
                    'is_active','role','name'
                ],
                'ignoreFromCreate' => [
                    'notes','is_active','name'
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
                'role' => 'required|exists:roles,name',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'png_image' => $validator->imageRule(false),
            ];
            if ($validator->isUpdatedRequest()){
                $RulesAll['email'] = 'required|email|unique:users,email,' . $validator->user->id;
            }
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
        return $this->resolveRoutes('user');
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
