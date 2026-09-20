<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GerenteGeralSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Gerente Geral',
            'email' => 'gerente.geral@banco.test',
            'password' => Hash::make('senha123'),
            'role_id' => 1, // GERENTE GERAL
            'email_verified_at' => now(),
        ]);
    }
}