<?php

namespace App\Repositories;

use App\Contracts\SettingInterface;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class EloquentSettingRepository extends BaseRepository implements SettingInterface
{
    public function __construct(Setting $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}


