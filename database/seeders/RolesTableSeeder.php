<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $roles = [
            [
                'id' => 1,
                'name' => 'Cliente',
                'description' => 'Cliente del restaurante',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'name' => 'Mozo/Moza',
                'description' => 'Mozo o camarero del restaurante',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'name' => 'Supervisor',
                'description' => 'Supervisor del restaurante',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'name' => 'Gerente',
                'description' => 'Gerente del restaurante',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                $role
            );
        }
    }
}

