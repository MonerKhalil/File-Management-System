<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\ISettingControlRepository;
use App\Models\SettingControl;

class SettingControlRepository extends BaseRepository implements ISettingControlRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return SettingControl::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return SettingControl::query();
    }
}
