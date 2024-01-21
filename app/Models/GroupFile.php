<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;
use App\HelperClasses\MyApp;

class GroupFile extends Model
{
    use HasFactory;

    const STATUS = ["pending","approve","reject"];

    protected $fillable = [
        "id_group" ,"id_file","status","can_share_with_user",
        "is_active","created_by","updated_by","notes",
    ];
}
