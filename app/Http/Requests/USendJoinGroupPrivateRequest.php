<?php

namespace App\Http\Requests;

use App\Models\Group;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class USendJoinGroupPrivateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "id_group" => ["required","integer",Rule::exists("groups","id")->where("type","private")]
        ];
    }
}
