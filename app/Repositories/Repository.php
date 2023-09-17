<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

class Repository 
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $userDetails): Model
    {
        return $this->model->create($userDetails);
    }

    public function one(array $params): ?Model
    {
        return $this->model->where($params)->first();
    }

    public function updateExist(Model $user, array $params): Model
    {
        $user->fill($params)->save();
        return $user;
    }

}
