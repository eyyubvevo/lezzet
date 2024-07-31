<?php

namespace App\Repositories;

use App\Contracts\NewsInterface;
use App\Models\News;
use Illuminate\Database\Eloquent\Model;

class EloquentNewsRepository extends BaseRepository implements NewsInterface
{
    public function __construct(News $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
