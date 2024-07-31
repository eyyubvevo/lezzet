<?php

namespace App\Repositories;

use App\Contracts\TagInterface;
use App\Models\Tag;
use \Illuminate\Database\Eloquent\Model;

class EloquentTagRepository extends BaseRepository implements TagInterface
{
    public function __construct(Tag $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
