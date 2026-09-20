<?php

namespace Database\Seeders;

use App\Models\Conta;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DadosTesteSeeder extends Seeder
{
    public function run(): void
    {
        $gerenteConta = User::create([
            'name' => 'Gerente Teste',
            'email' => 'gerente.conta@banco.test',
            'password' => Hash::make('senha123'),
            'role_id' => 2,
            'email_verified_at' => now(),
        ]);

        $cliente = User::create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@banco.test',
            'password' => Hash::make('senha123'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        Conta::create([
            'user_id' => $cliente->id,
            'gerente_conta_id' => $gerenteConta->id,
            'saldo' => 1000,
            'limite' => 500,
        ]);
    }
}