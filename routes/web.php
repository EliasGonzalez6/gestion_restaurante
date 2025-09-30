<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AuthController;

// Página de inicio
Route::get('/', [MenuController::class, 'index'])->name('welcome');

// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// CRUD de usuarios (solo con auth)
Route::middleware(['auth'])->group(function () {

    // Panel de administración de menú (restricción por rol se maneja en el controlador)
    Route::get('/admin/menu', [AdminMenuController::class, 'index'])->name('admin.menu.index');

    // Categorías
    Route::post('/admin/menu/category', [AdminMenuController::class, 'storeCategory'])->name('admin.menu.category.store');
    Route::get('/admin/menu/category/{category}/edit', [AdminMenuController::class, 'editCategory'])->name('admin.menu.category.edit');
    Route::put('/admin/menu/category/{category}', [AdminMenuController::class, 'updateCategory'])->name('admin.menu.category.update');
    Route::delete('/admin/menu/category/{category}', [AdminMenuController::class, 'destroyCategory'])->name('admin.menu.category.destroy');

    // Platos
    Route::post('/admin/menu/item', [AdminMenuController::class, 'storeItem'])->name('admin.menu.item.store');
    Route::get('/admin/menu/item/{item}/edit', [AdminMenuController::class, 'editItem'])->name('admin.menu.item.edit');
    Route::put('/admin/menu/item/{item}', [AdminMenuController::class, 'updateItem'])->name('admin.menu.item.update');
    Route::delete('/admin/menu/item/{item}', [AdminMenuController::class, 'destroyItem'])->name('admin.menu.item.destroy');
    Route::post('/admin/menu/item/{item}/toggle', [AdminMenuController::class, 'toggleItemOut'])->name('admin.menu.item.toggle');
    // Perfil de usuario autenticado
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Ruta para ver el perfil
    \App\Http\Controllers\ProfileController::class;
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
});
