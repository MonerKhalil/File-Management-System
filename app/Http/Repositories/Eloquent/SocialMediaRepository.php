<?php

namespace App\Http\Repositories\Eloquent;

use App\Http\Repositories\Interfaces\ISocialMediaRepository;
use App\Models\SocialMedia;

class SocialMediaRepository extends BaseRepository implements ISocialMediaRepository
{
    /**
     * @inheritDoc
     */
    function model()
    {
        return SocialMedia::class;
    }

    /**
     * @inheritDoc
     */
    function queryModel()
    {
        return SocialMedia::query();
    }
}
