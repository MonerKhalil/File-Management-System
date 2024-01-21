<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Support\Arr;

class UGroupRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = (new GroupRequest())->rules();
        return Arr::except($rules,["id_user"]);
    }
}
