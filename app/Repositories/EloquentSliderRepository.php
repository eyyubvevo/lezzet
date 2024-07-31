<?php

namespace App\Repositories;

use App\Contracts\SliderInterface;
use App\Models\Slider;
use \Illuminate\Database\Eloquent\Model;

class EloquentSliderRepository extends BaseRepository implements SliderInterface
{
    public function __construct(Slider $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
