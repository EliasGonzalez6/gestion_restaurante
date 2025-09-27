@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Usuario</h1>
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" value="{{ old('dni', $user->dni) }}">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Dirección</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Foto de perfil</label><br>
            @if($user->photo)
                <img src="{{ asset('storage/'.$user->photo) }}" width="60" class="mb-2 rounded-circle">
            @endif
            <input type="file" name="photo" class="form-control">
        </div>
        <div class="mb-3">
            <label for="roles_id" class="form-label">Rol</label>
            <select name="roles_id" class="form-select" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol->id }}" @if($user->roles_id == $rol->id) selected @endif>{{ $rol->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
