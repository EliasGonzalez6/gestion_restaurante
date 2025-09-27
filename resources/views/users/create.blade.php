@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Usuario</h1>
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="dni" class="form-label">DNI</label>
            <input type="text" name="dni" class="form-control" value="{{ old('dni') }}">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">Teléfono</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Dirección</label>
            <input type="text" name="address" class="form-control" value="{{ old('address') }}">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Foto de perfil</label>
            <input type="file" name="photo" class="form-control" id="photoInput" accept="image/*" onchange="previewPhoto(event)">
            <div class="mt-2">
                <img id="photoPreview" src="#" alt="Previsualización" style="display:none;max-width:120px;max-height:120px;object-fit:cover;" class="rounded-circle border">
            </div>
        </div>
        <div class="mb-3">
            <label for="roles_id" class="form-label">Rol</label>
            <select name="roles_id" class="form-select" required>
                @foreach($roles as $rol)
                    @if(!(Auth::user()->roles_id == 3 && $rol->id == 4))
                        <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
