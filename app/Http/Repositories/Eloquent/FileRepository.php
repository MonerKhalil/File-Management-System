<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\IFileRepository;
use App\Models\File;

class FileRepository extends BaseRepository implements IFileRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return File::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return File::query();
    }
}
