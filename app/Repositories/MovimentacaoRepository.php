<?php

namespace App\Repositories;

use App\Models\Movimentacao;

class MovimentacaoRepository extends BaseRepository
{
    public function __construct(protected Movimentacao $model) {}

    protected function getModel(): mixed
    {
        return $this->model;
    }

    public function porConta(int|string $contaId, ?string $inicio = null, ?string $fim = null)
    {
        $query = $this->model->where('conta_id', $contaId)->orderByDesc('created_at');

        if ($inicio) {
            $query->whereDate('created_at', '>=', $inicio);
        }

        if ($fim) {
            $query->whereDate('created_at', '<=', $fim);
        }

        return $query->get();
    }
}
