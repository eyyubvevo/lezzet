<?php

namespace App\Repositories;

use App\Contracts\ProductImageInterface;
use App\Models\ProductImage;

class EloquentProductImageRepository extends BaseRepository implements ProductImageInterface
{
    public function __construct(ProductImage $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
