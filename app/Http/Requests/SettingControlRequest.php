<?php

namespace App\Http\Requests;

use App\Models\SettingControl;

class SettingControlRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $callback = (new SettingControl)->validationBackEnd();

        return $callback($this);
    }
}
