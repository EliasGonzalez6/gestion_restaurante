@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Perfil</h1>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
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
        <hr>
        <h5 class="mt-4">Cambiar contraseña</h5>
        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña actual</label>
            <input type="password" name="current_password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Nueva contraseña</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Foto de perfil</label><br>
            @if($user->photo)
                <img src="{{ asset('storage/'.$user->photo) }}" width="60" class="mb-2 rounded-circle" id="currentPhoto">
            @else
                <img src="{{ asset('storage/photos/fotousuario.png') }}" width="60" class="mb-2 rounded-circle" id="currentPhoto">
            @endif
            <input type="file" name="photo" class="form-control" id="photoInput" accept="image/*" onchange="previewPhoto(event)">
            <div class="mt-2">
                <img id="photoPreview" src="#" alt="Previsualización" style="display:none;max-width:120px;max-height:120px;object-fit:cover;" class="rounded-circle border">
            </div>
        </div>
        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewPhoto(event) {
    const input = event.target;
    const preview = document.getElementById('photoPreview');
    const current = document.getElementById('currentPhoto');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if(current) current.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
        if(current) current.style.display = 'block';
    }
}
</script>
@endpush
