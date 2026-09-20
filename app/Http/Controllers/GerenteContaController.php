<?php

namespace App\Http\Controllers;

use App\Services\GerenteContaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GerenteContaController extends Controller
{
    public function __construct(protected GerenteContaService $service) {}

    private function checarAcesso()
    {
        if (Auth::user()->role_id !== 1) {
            abort(403);
        }
    }

    public function index()
    {
        $this->checarAcesso();
        $gerentes = $this->service->all();
        return view('gerentes.index', compact('gerentes'));
    }

    public function create()
    {
        $this->checarAcesso();
        return view('gerentes.create');
    }

    public function store(Request $request)
    {
        $this->checarAcesso();

        $data = $request->validate([
            'name' => 'required|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $this->service->store($data);

        return redirect()->route('gerentes.index')->with('status', 'Gerente de Conta criado.');
    }

    public function edit(string $id)
    {
        $this->checarAcesso();
        $gerente = $this->service->find($id);
        return view('gerentes.edit', compact('gerente'));
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

        return redirect()->route('gerentes.index')->with('status', 'Gerente de Conta atualizado.');
    }

    public function destroy(string $id)
    {
        $this->checarAcesso();
        $this->service->remove($id);
        return redirect()->route('gerentes.index')->with('status', 'Gerente de Conta removido.');
    }
}
