<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('welcome')
                ->with('success', 'Has iniciado sesión correctamente.');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no son correctas.',
        ])->onlyInput('email');
    }

    /**
     * Mostrar formulario de registro
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Procesar registro
     */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:3|max:255|regex:/^[\pL\s\-]+$/u',
            'email'    => 'required|string|email|max:255|unique:users|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/',
            'password' => 'required|string|min:6|confirmed',
            'dni'      => 'required|string|min:7|max:10|unique:users|regex:/^[0-9]+$/',
            'phone'    => 'required|string|min:8|max:20|regex:/^[\+]?[0-9\s\-()]+$/',
            'address'  => 'required|string|min:5|max:255',
            'photo'    => 'nullable|image|mimes:jpg,jpeg,png|max:10240|dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000',
        ], [
            // Mensajes personalizados
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.min' => 'El DNI debe tener al menos 7 dígitos.',
            'dni.max' => 'El DNI no puede tener más de 10 dígitos.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'dni.regex' => 'El DNI solo puede contener números.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.min' => 'El teléfono debe tener al menos 8 caracteres.',
            'phone.regex' => 'Formato de teléfono inválido.',
            'address.required' => 'La dirección es obligatoria.',
            'address.min' => 'La dirección debe tener al menos 5 caracteres.',
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.mimes' => 'La foto debe ser formato JPG, JPEG o PNG.',
            'photo.max' => 'La foto no puede superar los 2MB.',
            'photo.dimensions' => 'La imagen debe tener entre 100x100 y 4000x4000 píxeles.',
        ]);

        // Manejo de la foto
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('users', env('APP_PUBLIC_PATH'));
        }

        // Por defecto el rol es cliente (id = 1) si no se asigna otro
        $roleId = $request->roles_id ?? 1;

        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower($request->email),
            'password' => Hash::make($request->password),
            'dni'      => $request->dni,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'photo'    => $photoPath,
            'roles_id' => $roleId,
        ]);

        Auth::login($user);

        return redirect()->route('welcome')
            ->with('success', 'Usuario registrado correctamente.');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')
            ->with('success', 'Has cerrado sesión correctamente.');
    }
}
