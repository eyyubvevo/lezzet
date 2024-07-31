<?php

namespace App\Repositories;

use App\Contracts\PageMetaTagInterface;
use App\Models\PageMetaTag;

class EloquentPageMetaTagRepository extends BaseRepository implements PageMetaTagInterface
{
    public function __construct(PageMetaTag $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
