<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class UserController extends BaseController
{

     public function __construct()
    {
        // Middleware inline para reemplazar adminMiddleware
        $this->middleware(function ($request, $next) {
            if (!in_array(Auth::user()->roles_id, [3, 4])) {
                abort(403, 'Acceso no autorizado'); // Solo Gerente o Supervisor
            }
            return $next($request);
        })->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $users = User::with('rol')->get();
        return view('users.dashboard', compact('users'));
    }

    public function create()
    {
        // Solo supervisores y gerentes pueden asignar roles administrativos
        // Si es supervisor, no puede crear gerentes
        if (Auth::user()->roles_id == 3) {
            $roles = Rol::where('id', '!=', 4)->get(); // Excluir gerente
        } else {
            $roles = Rol::all();
        }
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {

        $rules = [
            'name'=>'required',
            'email'=>'required|email|unique:users,email|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/',
            'password'=>'required|min:6',
            'dni'=>'nullable|string|max:20',
            'phone'=>'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'photo'=>'nullable|image|max:2048',
            'roles_id'=>'required|exists:roles,id',
        ];
        if (Auth::user()->roles_id == 3) {
            // Supervisor no puede crear gerente
            $rules['roles_id'] .= ',id,!4';
        }
        $request->validate($rules);
        if (Auth::user()->roles_id == 3 && $request->roles_id == 4) {
            abort(403, 'No autorizado a crear gerentes');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos','public');
        }

        User::create([
            'name'=>$request->name,
            'email'=>strtolower($request->email),
            'password'=>Hash::make($request->password),
            'dni'=>$request->dni,
            'phone'=>$request->phone,
            'address'=>$request->address,
            'photo'=>$photoPath,
            'roles_id'=>$request->roles_id,
        ]);

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        // Si es supervisor, no puede asignar gerente
        if (Auth::user()->roles_id == 3) {
            $roles = Rol::where('id', '!=', 4)->get();
        } else {
            $roles = Rol::all();
        }
        return view('users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$user->id.'|regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/',
            'dni'=>'nullable|string|max:20',
            'phone'=>'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'photo'=>'nullable|image|max:2048',
            'roles_id'=>'required|exists:roles,id',
        ];
        if (Auth::user()->roles_id == 3) {
            // Supervisor no puede asignar gerente
            $rules['roles_id'] .= ',id,!4';
        }
        $request->validate($rules);
        if (Auth::user()->roles_id == 3 && $request->roles_id == 4) {
            abort(403, 'No autorizado a asignar gerente');
        }

    $data = $request->only(['name','email','dni','phone','address','roles_id']);
    $data['email'] = strtolower($data['email']);
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos','public');
            $data['photo'] = $photoPath;
        }
        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        // Solo gerente puede eliminar usuarios
        if (Auth::user()->roles_id != 4) {
            abort(403, 'No autorizado a eliminar usuarios');
        }
        $user->delete();
        return redirect()->route('users.index');
    }
}
