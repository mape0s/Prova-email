<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MovimentacaoService;
use Exception;
use Illuminate\Http\Request;

class MovimentacaoController extends Controller
{
    public function __construct(protected MovimentacaoService $service) {}

    public function extrato(Request $request)
    {
        $conta = $request->user()->conta;

        if ($conta->status === 'bloqueada') {
            return response()->json([
                'message' => 'Conta bloqueada, nao e possivel gerar extrato.',
            ], 422);
        }

        $movimentacoes = $this->service->extrato(
            $conta->id,
            $request->query('inicio'),
            $request->query('fim')
        );

        return response()->json($movimentacoes);
    }

    public function pix(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string|max:150',
        ]);

        $conta = $request->user()->conta;

        try {
            $this->service->pix(
                $conta,
                $data['email'],
                (float) $data['valor'],
                $data['descricao'] ?? ''
            );
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Pix realizado.']);
    }

    public function aplicar(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|in:cdb,cdi,poupanca',
            'valor' => 'required|numeric|min:0.01',
        ]);

        $conta = $request->user()->conta;

        try {
            $this->service->aplicar($conta, $data['tipo'], (float) $data['valor']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Aplicacao realizada.']);
    }

    public function resgatar(Request $request)
    {
        $data = $request->validate([
            'tipo' => 'required|in:cdb,cdi,poupanca',
            'valor' => 'required|numeric|min:0.01',
        ]);

        $conta = $request->user()->conta;

        try {
            $this->service->resgatar($conta, $data['tipo'], (float) $data['valor']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Resgate realizado.']);
    }
}
