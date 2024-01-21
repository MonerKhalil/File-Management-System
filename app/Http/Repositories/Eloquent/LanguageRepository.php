<?php

namespace App\Http\Repositories\Eloquent;

use App\HelperClasses\LanguageProcess;
use App\Http\Repositories\Interfaces\ILanguageRepository;
use App\Models\Language;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LanguageRepository extends BaseRepository implements ILanguageRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return Language::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return Language::query();
    }

    public function postStoreBehaviour(Model $model, array $data){
        Cache::forget(LanguageProcess::CACHE_LANG);
    }

    public function postUpdateBehaviour(Model $model, array $data){
        Cache::forget(LanguageProcess::CACHE_LANG);
    }

    public function postDestroyBehaviour(){
        Cache::forget(LanguageProcess::CACHE_LANG);
    }
}
