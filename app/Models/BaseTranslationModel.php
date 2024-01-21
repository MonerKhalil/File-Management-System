<?php

namespace App\Models;

use App\HelperClasses\MyApp;

abstract class BaseTranslationModel extends BaseModel
{
    /**
     * @param string $attribute
     * @return mixed
     * @author moner khalil
     */
    public function attributeTranslation(string $attribute): mixed
    {
        $id_local = MyApp::Classes()->languageProcess->langLocal->id ?? null;
        if (!is_null($id_local)){
            $translation = null;
            foreach ($this->translation as $item){
                if ($item->local_id == $id_local) {
                    $translation = $item;
                    break;
                }
            }
            if (!is_null($translation)){
                return $translation->{$attribute};
            }
        }
        return $this->{$attribute};
    }

    public abstract function attributesTranslations():array;

    public abstract function translation();
}
