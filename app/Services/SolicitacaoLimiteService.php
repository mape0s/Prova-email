<?php

namespace App\Services;

use App\Repositories\ContaRepository;
use App\Repositories\SolicitacaoLimiteRepository;

class SolicitacaoLimiteService extends BaseService
{
    public function __construct(
        protected SolicitacaoLimiteRepository $repository,
        protected ContaRepository $contaRepository
    ) {}

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function paraGerente(int $gerenteId)
    {
        return $this->repository->listByGerente($gerenteId);
    }

    public function clientesDoGerente(int $gerenteId)
    {
        return $this->contaRepository->clientesDoGerente($gerenteId);
    }

    public function contaDoGerente(int|string $contaId, int $gerenteId)
    {
        return $this->contaRepository->contaDoGerente($contaId, $gerenteId);
    }

    public function solicitar(int|string $contaId, float $valor)
    {
        return $this->repository->store([
            'conta_id' => $contaId,
            'valor_solicitado' => $valor,
            'status' => 'pendente',
        ]);
    }

    public function aprovar(int|string $id, int|string $gerenteGeralId)
    {
        $solicitacao = $this->repository->find($id);

        if (! $solicitacao || $solicitacao->status !== 'pendente') {
            abort(404);
        }

        $this->contaRepository->update([
            'limite' => $solicitacao->valor_solicitado
        ], $solicitacao->conta_id);

        return $this->repository->update([
            'status' => 'aprovado',
            'aprovado_por' => $gerenteGeralId,
        ], $id);
    }

    public function reprovar(int|string $id, int|string $gerenteGeralId)
    {
        $solicitacao = $this->repository->find($id);

        if (! $solicitacao || $solicitacao->status !== 'pendente') {
            abort(404);
        }

        return $this->repository->update([
            'status' => 'reprovado',
            'aprovado_por' => $gerenteGeralId,
        ], $id);
    }
}
