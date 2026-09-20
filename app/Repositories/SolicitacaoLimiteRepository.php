<?php

namespace App\Repositories;

use App\Models\SolicitacaoLimite;

class SolicitacaoLimiteRepository extends BaseRepository
{
    public function __construct(protected SolicitacaoLimite $model) {}

    protected function getModel(): mixed
    {
        return $this->model;
    }
}
