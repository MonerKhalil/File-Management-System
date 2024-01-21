<?php

namespace App\Http\Requests;

use App\Models\User;

class UserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $callback = (new User)->validationBackEnd();

        return $callback($this);
    }
}
