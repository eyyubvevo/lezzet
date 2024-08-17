<?php

namespace App\Repositories;

use App\Contracts\GeneralQuestionInterface;
use App\Models\GeneralQuestion;
use Illuminate\Database\Eloquent\Model;

class EloquentGeneralQuestionRepository extends BaseRepository implements GeneralQuestionInterface
{
    public function __construct(GeneralQuestion $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}

