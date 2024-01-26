<?php

namespace App\Http\Repositories\Eloquent;

use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\IMediaManagerRepository;
use App\Models\MediaManager;
use Illuminate\Database\Eloquent\Model;

class MediaManagerRepository extends BaseRepository implements IMediaManagerRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return MediaManager::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return MediaManager::query();
    }

    public function preStoreBehaviour(array $data){
        $data["user_id"] = MyApp::Classes()->getUser()->id;
        if (isset($data['name']) && !is_null($data['name'])){
            $data["file_name"] = $data['name'];
        }
        return $data;
    }

    public function preUpdateBehaviour(Model $model, array $data){
        if (isset($data['name']) && !is_null($data['name'])){
            $data["file_name"] = $data['name'];
        }
        return $data;
    }


}
