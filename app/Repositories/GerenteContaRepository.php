<?php

namespace App\Repositories;

use App\Models\User;

class GerenteContaRepository extends BaseRepository
{
    public function __construct(protected User $model) {}

    protected function getModel(): mixed
    {
        return $this->model->where('role_id', 2);
    }
}
