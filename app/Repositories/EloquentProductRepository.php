<?php

namespace App\Repositories;

use App\Contracts\ProductInterface;
use App\Models\Partner;
use App\Models\Product;
use \Illuminate\Database\Eloquent\Model;

class EloquentProductRepository extends BaseRepository implements ProductInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
