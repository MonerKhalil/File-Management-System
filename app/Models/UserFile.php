<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use App\HelperClasses\MyApp;

class UserFile extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_user" ,"id_file",
        "is_active","created_by","updated_by","notes",
    ];
}
