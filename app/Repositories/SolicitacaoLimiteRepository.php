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

    public function listByGerente(int $gerenteId)
    {
        return $this->getModel()
            ->with('conta.cliente', 'aprovadoPor')
            ->whereHas('conta', function ($query) use ($gerenteId) {
                $query->where('gerente_conta_id', $gerenteId);
            })
            ->orderByDesc('id')
            ->get();
    }
}
