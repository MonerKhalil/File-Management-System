<?php

namespace App\Models;

use App\HelperClasses\CraftData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseGeneralModel extends Model
{
    const API_KEYS = "API_KEYS";

    public function craftResource(bool $withTranslation = true){
        $tempData = $this;
        $customAttrs = collect($this->customAttributes());
        $relations = $this->getRelations();
        if (sizeof($customAttrs) > 0){
            $customAttrs->each(function ($item,$key) use ($relations,$tempData){
                $tempData->{$key} = $item;
                if (isset($relations[$key])){
                    if (isset($this->relations[$key])){
                        unset($this->relations[$key]);
                    }
                    unset($relations[$key]);
                }
            });
        }
        $relations = $this->getRelations();
        foreach ($relations as $k_relation => $v_relation){
            if ($v_relation instanceof Collection){
                $tempData->{$k_relation} = CraftData::many($v_relation,$withTranslation);
                if (isset($this->relations[$k_relation])){
                    unset($this->relations[$k_relation]);
                }
            }elseif ($v_relation instanceof Model){
                $tempData->{$k_relation} = CraftData::single($v_relation,$withTranslation);
                if (isset($this->relations[$k_relation])){
                    unset($this->relations[$k_relation]);
                }
            }
        }
        return $tempData;
    }

    public abstract function customAttributes():array;

    public abstract function attributesFullProcess():array;
}
