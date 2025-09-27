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
        return view('users.index', compact('users'));
    }

    public function create()
    {
        // Solo supervisores y gerentes pueden asignar roles administrativos
    $roles = Rol::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'dni'=>'nullable|string|max:20',
            'phone'=>'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'photo'=>'nullable|image|max:2048',
            'roles_id'=>'required|exists:roles,id',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos','public');
        }

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
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
    $roles = Rol::all();
        return view('users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users,email,'.$user->id,
            'dni'=>'nullable|string|max:20',
            'phone'=>'nullable|string|max:20',
            'address'=>'nullable|string|max:255',
            'photo'=>'nullable|image|max:2048',
            'roles_id'=>'required|exists:roles,id',
        ]);


        $data = $request->only(['name','email','dni','phone','address','roles_id']);
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos','public');
            $data['photo'] = $photoPath;
        }
        $user->update($data);

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index');
    }
}
