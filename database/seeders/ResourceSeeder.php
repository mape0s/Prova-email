<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResourceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('resources')->insert([
            ['name' => 'gerente-conta.manter'],
            ['name' => 'limite.aprovar'],
            ['name' => 'auditoria.visualizar'],
            ['name' => 'cliente.manter'],
            ['name' => 'extrato-cliente.visualizar'],
            ['name' => 'conta.bloquear'],
            ['name' => 'saldo.visualizar'],
            ['name' => 'extrato.visualizar'],
            ['name' => 'movimentacao.efetuar'],
        ]);
    }
}