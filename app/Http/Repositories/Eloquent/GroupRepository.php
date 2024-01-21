<?php

namespace App\Http\Repositories\Eloquent;

use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Models\Group;

class GroupRepository extends BaseRepository implements IGroupRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return Group::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return Group::query();
    }

    public function preStoreBehaviour(array $data){
        if (!isset($data["id_user"])){
            $data["id_user"] = MyApp::Classes()->getUser()->id;
        }
        return $data;
    }
}
