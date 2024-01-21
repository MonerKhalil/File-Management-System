<?php

namespace App\Http\Requests;

use App\Models\Setting;

class SettingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $callback = (new Setting)->validationBackEnd();

        return $callback($this);
    }
}
