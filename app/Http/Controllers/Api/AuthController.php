<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Credenciais invalidas.'],
            ]);
        }

        $user = Auth::user();

        if ($user->role_id !== 3) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => ['Acesso exclusivo para clientes.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken($request->device_name)->plainTextToken,
            'user' => $user->only(['id', 'name', 'email']),
        ]);
    }

    public function saldo(Request $request)
    {
        $conta = $request->user()->conta;

        if (! $conta) {
            return response()->json([
                'message' => 'Conta nao encontrada.'
            ], 404);
        }

        return response()->json([
            'saldo' => $conta->saldo,
            'limite' => $conta->limite,
            'status' => $conta->status,
            'saldo_cdb' => $conta->saldo_cdb,
            'saldo_cdi' => $conta->saldo_cdi,
            'saldo_poupanca' => $conta->saldo_poupanca,
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();

        if ($token) {
            $token->delete();
        }

        return response()->json([
            'message' => 'Logout ok.'
        ]);
    }
}
