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

    public function clientesDoGerente(int $gerenteId)
    {
        return $this->getModel()
            ->with('cliente')
            ->where('gerente_conta_id', $gerenteId)
            ->orderBy('id')
            ->get();
    }

    public function contaDoGerente(int|string $contaId, int $gerenteId): ?Conta
    {
        return $this->getModel()
            ->where('id', $contaId)
            ->where('gerente_conta_id', $gerenteId)
            ->first();
    }
}
