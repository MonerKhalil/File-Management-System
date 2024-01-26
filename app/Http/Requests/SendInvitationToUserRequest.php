<?php

namespace App\Http\Requests;

use App\Models\GroupFile;
use Illuminate\Validation\Rule;

class SendInvitationToUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "user_ids" => ["required","array"],
            "user_ids.*" => ["required",Rule::exists("users","id")],
        ];
    }
}
