<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'GERENTE GERAL'],
            ['name' => 'GERENTE CONTA'],
            ['name' => 'CLIENTE'],
        ]);
    }
}