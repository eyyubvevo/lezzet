<?php

namespace App\Repositories;

use App\Contracts\PartnerInterface;
use App\Models\Partner;
use \Illuminate\Database\Eloquent\Model;

class EloquentPartnerRepository extends BaseRepository implements PartnerInterface
{
    public function __construct(Partner $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
