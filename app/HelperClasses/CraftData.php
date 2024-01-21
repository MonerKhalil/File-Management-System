<?php

namespace App\HelperClasses;

use App\Models\BaseGeneralModel;
use App\Models\BaseTranslationModel;
use Illuminate\Pagination\LengthAwarePaginator;

class CraftData
{
    const KEYS_NAME_REQUEST = "keysArray";

    public static function many(mixed $collection,bool $withTranslation = true)
    {
        if ($collection instanceof LengthAwarePaginator){
            return $collection->through(function ($item) use ($withTranslation){
                return self::single($item,$withTranslation);
            });
        }
        return $collection?->map(function ($item) use ($withTranslation){
            if ($item instanceof BaseGeneralModel){
                return self::single($item,$withTranslation);
            }
            return $item;
        });
    }

    public static function single(mixed $model,bool $withTranslation = true)
    {
        if ($model instanceof BaseGeneralModel){
            $dataModelFinal = null;
            $keysModel = self::getKeysInModel($model);
            if (!is_null($keysModel)){
                if (sizeof($keysModel) > 0){
                    $dataModelFinal = $model->craftResource($withTranslation)->only($keysModel);
                }
            }
            $dataModelFinal = is_null($dataModelFinal) ? $model->craftResource($withTranslation) : $dataModelFinal;
            return (!$withTranslation && ($dataModelFinal instanceof BaseTranslationModel))
                ? self::resolveDataWithOutTranslationRelation($dataModelFinal) : $dataModelFinal;
        }
        return $model;
    }

    private static function getKeysInModel($model){
        $key = request()->{self::KEYS_NAME_REQUEST};
        if (!is_null($key) && $model instanceof BaseGeneralModel){
            $attributesKeys = $model->attributesFullProcess();
            return $attributesKeys[$key] ?? null;
        }
        return null;
    }

    private static function resolveDataWithOutTranslationRelation($data){
        $tempData = $data;
        foreach ($data->attributesTranslations() as $key){
            $tempData->{$key} = checkObjectInstanceofTranslation($tempData,$key);
        }
        if (isset($tempData->translation)){
            unset($tempData->translation);
        }
        return $tempData;
    }
}
