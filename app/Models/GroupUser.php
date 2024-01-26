<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use App\HelperClasses\MyApp;

class GroupUser extends Model
{
    use HasFactory;

    const TYPE = ["request_group","request_user","none"];
    
    const STATUS = ["pending","approve","reject"];

    protected $fillable = [
        "id_user","id_group","is_request","type_request","status",
        "is_active","created_by","updated_by","notes",
    ];

    public function user(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function group(){
        return $this->belongsTo(Group::class,"id_group","id");
    }
}
