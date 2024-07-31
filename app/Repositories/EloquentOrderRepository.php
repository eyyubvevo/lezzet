<?php

namespace App\Repositories;

use App\Contracts\OrderInterface;
use App\Models\Order;

class EloquentOrderRepository extends BaseRepository implements OrderInterface
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

}

