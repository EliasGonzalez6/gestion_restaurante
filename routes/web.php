<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/create-user', function () {
    User::create([
        'name' => 'Elias Gonzalez',
        'email' => 'Elias@gmail.com',
        'password' => Hash::make('1234'), // Nunca guardes en texto plano
        'dni' => '96121155',
        'phone' => '123456789',
        'address' => 'Av. Siempre Viva 742',
        'photo' => 'Elias.jpg', // Podés guardar la ruta o filename
        'roles_id' => 4, // Mozo (id 2 en tu seeder de roles)
    ]);

    return "✅ Usuario Eli creado correctamente";
});

Route::get('/list', function () {
    $users = User::with('rol')->get();

    $output = "<h2>Listado de Usuarios</h2><ul>";
    foreach ($users as $user) {
        $output .= "<li>
            <strong>Nombre:</strong> {$user->name} <br>
            <strong>Email:</strong> {$user->email} <br>
            <strong>Rol:</strong> {$user->rol->name} <br>
            <hr>
        </li>";
    }
    $output .= "</ul>";

    return $output;
});

Route::get('/list-users', function () {
    $users = User::with('rol')->get();

    return response()->json($users);
});

Route::get('/edit-user/{id}', function ($id) {
    $user = User::find($id); // Buscamos el usuario por id

    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    // Cambiamos los datos que queramos
    $user->roles_id = 1; // Cambiamos el rol a Supervisor (id 3)

    $user->save(); // Guardamos los cambios

    return response()->json([
        'message' => 'Usuario actualizado correctamente',
        'user' => $user->load('rol') // Cargamos la relación rol
    ]);
});


Route::get('/delete-user/{id}', function ($id) {
    $user = User::find($id);

    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }

    $user->delete(); // Borra el usuario de la base de datos

    return response()->json([
        'message' => 'Usuario borrado correctamente',
        'deleted_user_id' => $id
    ]);
});