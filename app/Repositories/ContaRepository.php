<?php

namespace App\Repositories;

use App\Models\Conta;

class ContaRepository extends BaseRepository
{
    public function __construct(protected Conta $model) {}

    protected function getModel(): mixed
    {
        return $this->model;
    }
}
