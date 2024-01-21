<?php

namespace App\Http\Repositories\Eloquent;

use App\HelperClasses\MyApp;
use App\Http\Repositories\Interfaces\IGroupRepository;
use App\Http\Repositories\Interfaces\IGroupUserRepository;
use App\Models\Group;
use App\Models\GroupUser;

class GroupUserRepository extends BaseRepository implements IGroupUserRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return GroupUser::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return GroupUser::query();
    }
}
