<?php

namespace App\Rules;

use App\Http\Repositories\Eloquent\MediaManagerRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class FileMediaRule implements Rule
{
    private $MediaManagerRepository = null;
    private $types = null,$size = null;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($types,$size)
    {
        $this->MediaManagerRepository = (new MediaManagerRepository(app()));
        $this->types = $types;
        $this->size = $size;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_numeric($value)){
            $item = $this->MediaManagerRepository->queryModelWithActive()->where("id",$value)->first();
            if (is_null($item)){
                return false;
            }
            if (!in_array($item->extension,$this->types)){
                return false;
            }
            return true;
        }elseif(is_file($value) || $value instanceof UploadedFile){
            if (!in_array($value->getClientOriginalExtension(),$this->types)){
                return false;
            }
            $bytes = number_format($value->getSize() / 1024, 2);// KB
            $mainSize = number_format($this->size / 1024 ,2);// KB

            if ($mainSize < $bytes){
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The id media manage is not exist | the file not ext ' . implode(",",$this->types) . " | the file is max ".$this->size." .";
    }
}
