<?php

namespace App\Http\Requests;

use App\Models\File;

class FileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $callback = (new File)->validationBackEnd();

        return $callback($this);
    }
}
