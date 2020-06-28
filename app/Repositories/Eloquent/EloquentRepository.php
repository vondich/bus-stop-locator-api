<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Contracts\Repositories\BaseRepository;

class EloquentRepository implements BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * {@inheritdoc}
     */
    public function with(array $with = []): BaseRepository
    {
        $this->with = $with;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function findById(int $id) : ?Model
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }
}
