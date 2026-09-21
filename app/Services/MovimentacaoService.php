<?php

namespace App\Services;

use App\Models\Conta;
use App\Models\User;
use App\Repositories\ContaRepository;
use App\Repositories\MovimentacaoRepository;
use Exception;
use Illuminate\Support\Facades\DB;

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

    public function pix(Conta $conta, string $emailDestino, float $valor, string $descricao = '')
    {
        if ($conta->status === 'bloqueada') {
            throw new Exception('Conta bloqueada, nao e possivel movimentar.');
        }

        $destinatario = User::query()
            ->where('email', $emailDestino)
            ->where('role_id', 3)
            ->first();

        if (! $destinatario || ! $destinatario->conta) {
            throw new Exception('Nao existe cliente com este e-mail.');
        }

        $contaDestino = $destinatario->conta;

        if ($contaDestino->id === $conta->id) {
            throw new Exception('Nao e possivel enviar PIX para a propria conta.');
        }

        if ($contaDestino->status === 'bloqueada') {
            throw new Exception('A conta do destinatario esta bloqueada.');
        }

        if ($conta->saldo < $valor) {
            throw new Exception('Saldo insuficiente.');
        }

        DB::transaction(function () use ($conta, $contaDestino, $valor, $descricao) {
            $descricaoSaida = $descricao !== ''
                ? $descricao
                : 'Pix enviado';

            $this->contaRepository->update([
                'saldo' => $conta->saldo - $valor,
            ], $conta->id);

            $this->contaRepository->update([
                'saldo' => $contaDestino->saldo + $valor,
            ], $contaDestino->id);

            $this->repository->store([
                'conta_id' => $conta->id,
                'tipo' => 'pix',
                'valor' => $valor,
                'natureza' => 'saida',
                'descricao' => $descricaoSaida . ' para ' . $contaDestino->cliente->email,
            ]);

            $this->repository->store([
                'conta_id' => $contaDestino->id,
                'tipo' => 'pix',
                'valor' => $valor,
                'natureza' => 'entrada',
                'descricao' => 'Pix recebido de ' . $conta->cliente->email,
            ]);
        });
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
