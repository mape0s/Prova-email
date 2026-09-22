<?php

namespace App\Http\Controllers;

use App\Services\ClienteService;
use App\Services\MovimentacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function __construct(
        protected ClienteService $service,
        protected MovimentacaoService $movimentacaoService
    ) {}

    private function checarAcesso()
    {
        if (Auth::user()->role_id !== 2) {
            abort(403);
        }
    }

    public function index()
    {
        $this->checarAcesso();
        $clientes = $this->service->all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $this->checarAcesso();
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $this->checarAcesso();

        $data = $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'saldo' => 'nullable|numeric',
            'limite' => 'nullable|numeric',
        ]);

        $this->service->store($data);

        return redirect()->route('clientes.index')->with('status', 'Cliente criado.');
    }

    public function edit(string $id)
    {
        $this->checarAcesso();
        $cliente = $this->service->find($id);
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, string $id)
    {
        $this->checarAcesso();

        $data = $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $this->service->update($data, $id);

        return redirect()->route('clientes.index')->with('status', 'Cliente atualizado.');
    }

    public function destroy(string $id)
    {
        $this->checarAcesso();
        $this->service->remove($id);
        return redirect()->route('clientes.index')->with('status', 'Cliente removido.');
    }

    public function extrato(string $id)
    {
        $this->checarAcesso();

        $cliente = $this->service->find($id);
        $conta = $cliente->conta;

        $movimentacoes = $this->movimentacaoService->extrato($conta->id);

        return view('clientes.extrato', compact('cliente', 'conta', 'movimentacoes'));
    }
}
