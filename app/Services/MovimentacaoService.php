<?php

namespace App\Services;

use App\Models\Conta;
use App\Repositories\ContaRepository;
use App\Repositories\MovimentacaoRepository;
use Exception;

class MovimentacaoService extends BaseService
{
    public function __construct(
        protected MovimentacaoRepository $repository,
        protected ContaRepository $contaRepository
    ) {}

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function extrato(int|string $contaId, ?string $inicio = null, ?string $fim = null)
    {
        return $this->repository->porConta($contaId, $inicio, $fim);
    }

    public function pix(Conta $conta, float $valor, string $descricao = '')
    {
        if ($conta->status === 'bloqueada') {
            throw new Exception('Conta bloqueada, nao e possivel movimentar.');
        }

        if ($conta->saldo < $valor) {
            throw new Exception('Saldo insuficiente.');
        }

        $this->contaRepository->update(['saldo' => $conta->saldo - $valor], $conta->id);

        $this->repository->store([
            'conta_id' => $conta->id,
            'tipo' => 'pix',
            'valor' => $valor,
            'natureza' => 'saida',
            'descricao' => $descricao !== '' ? $descricao : 'Pix enviado',
        ]);
    }

    public function aplicar(Conta $conta, string $tipo, float $valor)
    {
        if ($conta->status === 'bloqueada') {
            throw new Exception('Conta bloqueada, nao e possivel movimentar.');
        }

        if ($conta->saldo < $valor) {
            throw new Exception('Saldo insuficiente.');
        }

        $campo = 'saldo_' . $tipo;

        $this->contaRepository->update([
            'saldo' => $conta->saldo - $valor,
            $campo => $conta->$campo + $valor,
        ], $conta->id);

        $this->repository->store([
            'conta_id' => $conta->id,
            'tipo' => 'aplicacao_' . $tipo,
            'valor' => $valor,
            'natureza' => 'saida',
            'descricao' => 'Aplicacao em ' . strtoupper($tipo),
        ]);
    }

    public function resgatar(Conta $conta, string $tipo, float $valor)
    {
        if ($conta->status === 'bloqueada') {
            throw new Exception('Conta bloqueada, nao e possivel movimentar.');
        }

        $campo = 'saldo_' . $tipo;

        if ($conta->$campo < $valor) {
            throw new Exception('Saldo insuficiente na aplicacao.');
        }

        $this->contaRepository->update([
            'saldo' => $conta->saldo + $valor,
            $campo => $conta->$campo - $valor,
        ], $conta->id);

        $this->repository->store([
            'conta_id' => $conta->id,
            'tipo' => 'resgate_' . $tipo,
            'valor' => $valor,
            'natureza' => 'entrada',
            'descricao' => 'Resgate de ' . strtoupper($tipo),
        ]);
    }
}
