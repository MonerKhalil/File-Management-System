<?php

namespace App\Http\Requests;

use App\Models\Language;

class LanguageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $callback = (new Language)->validationBackEnd();

        return $callback($this);
    }
}
