<?php

namespace App\Repositories;

use App\Contracts\UserInterface;
use App\Models\User;
use \Illuminate\Database\Eloquent\Model;

class EloquentUserRepository extends BaseRepository implements UserInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}
