<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamamos a todos los seeders que quieras ejecutar
        $this->call([
            RolesTableSeeder::class, // Seeder de roles
            UsersTableSeeder::class, // Seeder de usuarios
        ]);
    }
}
