<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        // Actualizamos los nombres y descripciones existentes
        DB::table('roles')->where('id', 1)->update([
            'name' => 'Cliente',
            'description' => 'Cliente',
            'updated_at' => $now
        ]);

        DB::table('roles')->where('id', 2)->update([
            'name' => 'Mozo/ Moza',
            'description' => 'Mozo / Camarero',
            'updated_at' => $now
        ]);

        DB::table('roles')->where('id', 3)->update([
            'name' => 'Supervisor',
            'description' => 'Supervisor',
            'updated_at' => $now
        ]);

        DB::table('roles')->where('id', 4)->update([
            'name' => 'Gerente',
            'description' => 'Gerente',
            'updated_at' => $now
        ]);
    }
}

