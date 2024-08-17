<?php

namespace App\Repositories;

use App\Contracts\ContactInterface;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;

class EloquentContactRepository extends BaseRepository implements ContactInterface
{
    public function __construct(Contact $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}

