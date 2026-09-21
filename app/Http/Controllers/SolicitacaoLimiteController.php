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
        if (! in_array(Auth::user()->role_id, [1, 2])) {
            abort(403);
        }

        $solicitacoes = $this->service->all();
        $solicitacoes->load('conta.cliente', 'aprovadoPor');

        return view('solicitacoes.index', compact('solicitacoes'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role_id !== 2) {
            abort(403);
        }

        $data = $request->validate([
            'valor_solicitado' => 'required|numeric|min:0.01',
        ]);

        $conta = Auth::user()->conta;

        if (! $conta) {
            abort(403);
        }

        $this->service->solicitar($conta->id, $data['valor_solicitado']);

        return back()->with('status', 'Solicitacao enviada.');
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
