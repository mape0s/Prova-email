<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Services\ContaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ContaController extends Controller
{
    public function __construct(protected ContaService $service) {}

    public function index()
    {
        if (Auth::user()->role_id !== 2) {
            abort(403);
        }

        $contas = Conta::with('cliente')
            ->whereNotNull('user_id')
            ->orderBy('id')
            ->get();

        return view('contas.index', compact('contas'));
    }

    public function bloquear(string $id)
    {
        $conta = $this->service->find($id);

        if (! $conta) {
            abort(404);
        }

        Gate::authorize('bloquear', $conta);

        $this->service->bloquear($id);

        return back()->with('status', 'Conta bloqueada.');
    }

    public function desbloquear(string $id)
    {
        $conta = $this->service->find($id);

        if (! $conta) {
            abort(404);
        }

        Gate::authorize('desbloquear', $conta);

        $this->service->desbloquear($id);

        return back()->with('status', 'Conta desbloqueada.');
    }
}
