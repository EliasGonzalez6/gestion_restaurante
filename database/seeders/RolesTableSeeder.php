<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('roles')->insert([
            ['name' => 'cliente', 'description' => 'Cliente', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'mozo', 'description' => 'Mozo / Camarero', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'supervisor', 'description' => 'Supervisor', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'gerente', 'description' => 'Gerente', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
