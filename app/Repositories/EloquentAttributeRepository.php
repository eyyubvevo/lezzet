<?php

namespace App\Repositories;

use App\Contracts\AttributeInterface;
use App\Models\Attribute;
use \Illuminate\Database\Eloquent\Model;

class EloquentAttributeRepository extends BaseRepository implements AttributeInterface
{
    public function __construct(Attribute $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
