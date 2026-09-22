<?php

namespace App\Http\Controllers;

use App\Services\SolicitacaoLimiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitacaoLimiteController extends Controller
{
    public function __construct(protected SolicitacaoLimiteService $service) {}

    public function index()
    {
        $role = (int) Auth::user()->role_id;

        if ($role === 1) {
            $solicitacoes = $this->service->all();
            $solicitacoes->load('conta.cliente', 'conta.gerenteConta', 'aprovadoPor');
            $clientes = collect();
        } elseif ($role === 2) {
            $solicitacoes = $this->service->paraGerente(Auth::id());
            $clientes = $this->service->clientesDoGerente(Auth::id());
        } else {
            abort(403);
        }

        return view('solicitacoes.index', compact('solicitacoes', 'clientes'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role_id !== 2) {
            abort(403);
        }

        $data = $request->validate([
            'conta_id' => 'required|integer',
            'valor_solicitado' => 'required|numeric|min:0.01',
        ]);

        $conta = $this->service->contaDoGerente(
            $data['conta_id'],
            Auth::id()
        );

        if (! $conta) {
            abort(403);
        }

        $this->service->solicitar($conta->id, $data['valor_solicitado']);

        return back()->with('status', 'Solicitacao enviada para aprovacao do Gerente Geral.');
    }

    public function aprovar(string $id)
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }

        $this->service->aprovar($id, Auth::id());

        return back()->with('status', 'Solicitacao aprovada.');
    }

    public function reprovar(string $id)
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }

        $this->service->reprovar($id, Auth::id());

        return back()->with('status', 'Solicitacao reprovada.');
    }
}
