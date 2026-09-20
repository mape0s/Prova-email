<?php

namespace App\Http\Controllers;

use App\Services\ContaService;
use Illuminate\Support\Facades\Gate;

class ContaController extends Controller
{
    public function __construct(protected ContaService $service) {}

    public function bloquear(string $id)
    {
        $conta = $this->service->find($id);
        Gate::authorize('bloquear', $conta);

        $this->service->bloquear($id);

        return back()->with('status', 'Conta bloqueada.');
    }

    public function desbloquear(string $id)
    {
        $conta = $this->service->find($id);
        Gate::authorize('desbloquear', $conta);

        $this->service->desbloquear($id);

        return back()->with('status', 'Conta desbloqueada.');
    }
}
