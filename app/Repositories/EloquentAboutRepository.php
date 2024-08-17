<?php

namespace App\Repositories;

use App\Contracts\AboutInterface;
use App\Models\About;
use Illuminate\Database\Eloquent\Model;

class EloquentAboutRepository extends BaseRepository implements AboutInterface
{
    public function __construct(About $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}

