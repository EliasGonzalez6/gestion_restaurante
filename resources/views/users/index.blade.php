@extends('layouts.app')

@section('content')
<h1>Usuarios</h1>
<a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Crear Usuario</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>
                @php
                    $photo = $user->photo ? Storage::url($user->photo) : asset('storage/photos/fotousuario.png');
                @endphp
                <img src="{{ $photo }}" width="50" style="object-fit:cover;max-height:50px;max-width:50px;" class="rounded-circle border">
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->dni }}</td>
            <td>{{ $user->phone }}</td>
            <td>{{ $user->address }}</td>
            <td>{{ $user->rol->name }}</td>
            <td>
                @php $isSupervisor = Auth::user()->roles_id == 3; @endphp
                @if(!($user->roles_id == 4 && $isSupervisor))
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Editar</a>
                @endif
                @if(Auth::user()->roles_id == 4)
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar usuario?')">Borrar</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
