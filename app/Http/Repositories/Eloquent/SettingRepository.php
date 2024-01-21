<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\ISettingRepository;
use App\Models\Setting;

class SettingRepository extends BaseRepository implements ISettingRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return Setting::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return Setting::query();
    }
}
