@extends('layouts.app')

@section('content')

<div class="edit-profile-title">Editar Perfil</div>
<div class="edit-profile-subtitle">Actualiza tu información personal</div>
<div class="edit-profile-container">
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-profile-form">
        @csrf
        @method('PUT')
        <!-- Alerta informativa -->
        <div class="edit-profile-alert">
            <i class="fa fa-info-circle"></i>
            <div><strong>Importante:</strong> Asegúrate de que toda la información sea correcta antes de guardar los cambios.</div>
        </div>
        <!-- Sección foto de perfil -->
        <div class="profile-photo-section">
            @if($user->photo)
                <img src="{{ asset('storage/'.$user->photo) }}" class="current-photo" id="currentPhoto">
            @else
                <img src="{{ asset('storage/photos/fotousuario.png') }}" class="current-photo" id="currentPhoto">
            @endif
            <div class="d-flex flex-column align-items-center mt-2">
                <label class="photo-upload-btn w-auto">
                    <i class="fa fa-camera"></i> <span class="photo-btn-text">Cambiar Foto</span>
                    <input type="file" name="photo" id="photoInput" accept="image/*" onchange="previewPhoto(event)">
                </label>
            </div>
            <div class="mt-2">
                <img id="photoPreview" src="#" alt="Previsualización" class="photo-preview" style="display:none; max-width:150px; max-height:150px; object-fit:cover; border-radius:50%; border:2px solid var(--dorado); box-shadow:0 2px 8px #d4af3722;" />
            </div>
        </div>
        <!-- Información Personal -->
        <div class="edit-profile-divider"></div>
        <div class="edit-profile-section-title"><i class="fa fa-user"></i> Información Personal</div>
        <div class="form-row">
            <div>
                <label for="name">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div>
                <label for="email">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>
        <div class="form-row">
            <div>
                <label for="phone">Teléfono</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            <div>
                <label for="dni">DNI</label>
                <input type="text" name="dni" value="{{ old('dni', $user->dni) }}" disabled>
            </div>
        </div>
        <div class="form-row-single">
            <label for="address">Dirección Completa</label>
            <input type="text" name="address" value="{{ old('address', $user->address) }}">
        </div>
        <!-- Seguridad -->
        <div class="edit-profile-divider"></div>
        <div class="edit-profile-section-title"><i class="fa fa-lock"></i> Seguridad</div>
        <div class="form-row">
            <div>
                <label for="current_password">Contraseña Actual</label>
                <input type="password" name="current_password" placeholder="Dejar en blanco si no deseas cambiar">
            </div>
            <div>
                <label for="password">Nueva Contraseña</label>
                <input type="password" name="password" placeholder="Nueva contraseña">
            </div>
        </div>
        <div class="form-row-single">
            <label for="password_confirmation">Confirmar Nueva Contraseña</label>
            <input type="password" name="password_confirmation" placeholder="Confirmar nueva contraseña">
        </div>
        <!-- Acciones -->
        <div class="form-actions">
            <button type="submit" class="btn-save"><i class="fa fa-save"></i> Guardar Cambios</button>
            <form action="{{ route('profile.show') }}" method="get" style="display:inline;">
                <button type="submit" class="btn-cancel"><i class="fa fa-times"></i> Cancelar</button>
            </form>
        </div>
    </form>
</div>
</div>
@push('scripts')
<script>
// Preview de foto al seleccionar archivo
document.getElementById('photoInput').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('currentPhoto').src = event.target.result;
            document.getElementById('photoPreview').src = event.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        };
        reader.readAsDataURL(e.target.files[0]);
    }
});
</script>
@endpush

@push('scripts')
<script>
function previewPhoto(event) {
    const input = event.target;
    const preview = document.getElementById('photoPreview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}
</script>
@endpush
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
