<?php

namespace App\Http\Controllers;

use App\Services\ContaService;
use Illuminate\Support\Facades\Auth;

class ContaController extends Controller
{
    public function __construct(protected ContaService $service) {}

    public function bloquear(string $id)
    {
        if (Auth::user()->role_id !== 2) {
            abort(403);
        }

        $this->service->bloquear($id);

        return back()->with('status', 'Conta bloqueada.');
    }

    public function desbloquear(string $id)
    {
        if (Auth::user()->role_id !== 2) {
            abort(403);
        }

        $this->service->desbloquear($id);

        return back()->with('status', 'Conta desbloqueada.');
    }
    use App\Http\Controllers\ContaController;

        Route::middleware('auth')->group(function () {
        Route::post('/contas/{id}/bloquear', [ContaController::class, 'bloquear'])->name('contas.bloquear');
        Route::post('/contas/{id}/desbloquear', [ContaController::class, 'desbloquear'])->name('contas.desbloquear');
    });

}