<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\SolicitacaoLimite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;

class AuditoriaController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }

        $audits = Audit::with('user')
            ->whereIn('auditable_type', [Conta::class, User::class, SolicitacaoLimite::class])
            ->latest()
            ->get();

        return view('auditoria.index', compact('audits'));
    }
}
