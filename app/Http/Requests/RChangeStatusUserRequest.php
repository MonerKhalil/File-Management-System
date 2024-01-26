<?php

namespace App\Http\Requests;

use App\Models\GroupUser;
use Illuminate\Validation\Rule;

class RChangeStatusUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "request_user_group_id" => ["required","array"],
            "request_user_group_id.*" => ["required",Rule::exists("group_users","id")
                ->where("is_request",1)->where("status","pending")
                ->where("type_request","request_user")],
            "status" => ["required",Rule::in(GroupUser::STATUS)],
        ];
    }
}
