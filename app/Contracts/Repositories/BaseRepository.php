<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{
    /**
     * Includes relationships in query.
     *
     * @param array $with
     * @return BaseRepository
     */
    public function with(array $with = []): BaseRepository;

    /**
     * Finds a model by its id
     * 
     * @param int $id
     * 
     * @return Model|null
     */
    public function findById(int $id) : ?Model;

    /**
     * Creates a new resource based on the specified data
     * 
     * @param array $data
     * 
     * @return Model
     */
    public function store(array $data): Model;
}
