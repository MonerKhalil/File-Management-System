<?php

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

class AddFileToMeRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "file_ids" => ["required","array"],
            "file_ids.*" => ["required",Rule::exists("media_managers","id")],
            "group_id" => ["required",Rule::exists("groups","id")],
        ];
    }
}
