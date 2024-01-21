<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\IMediaManagerRepository;
use App\Models\MediaManager;

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
}
