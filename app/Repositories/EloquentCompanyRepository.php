<?php

namespace App\Repositories;

use App\Contracts\CompanyInterface;
use App\Models\Company;

class EloquentCompanyRepository extends BaseRepository implements CompanyInterface
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }
}

