<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        $users = [
            [
                'name' => 'Gerente Principal',
                'email' => 'gerente@restaurante.com',
                'password' => Hash::make('gerente123'),
                'dni' => '12345678',
                'phone' => '0414-1234567',
                'address' => 'Caracas, Venezuela',
                'roles_id' => 4, // Gerente
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Supervisor Ejemplo',
                'email' => 'supervisor@restaurante.com',
                'password' => Hash::make('supervisor123'),
                'dni' => '23456789',
                'phone' => '0424-2345678',
                'address' => 'Caracas, Venezuela',
                'roles_id' => 3, // Supervisor
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Mozo Ejemplo',
                'email' => 'mozo@restaurante.com',
                'password' => Hash::make('mozo123'),
                'dni' => '34567890',
                'phone' => '0412-3456789',
                'address' => 'Caracas, Venezuela',
                'roles_id' => 2, // Mozo/Moza
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Cliente Ejemplo',
                'email' => 'cliente@restaurante.com',
                'password' => Hash::make('cliente123'),
                'dni' => '45678901',
                'phone' => '0416-4567890',
                'address' => 'Caracas, Venezuela',
                'roles_id' => 1, // Cliente
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                $user
            );
        }
    }
}
