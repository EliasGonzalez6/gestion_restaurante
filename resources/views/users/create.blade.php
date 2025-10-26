@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-user-form.css') }}">

<div class="profile-container">
    <div class="profile-card">
        <!-- Header -->
        <div class="profile-header">
            <div class="header-content">
                <h1 class="profile-title">
                    <i class="fas fa-user-plus"></i>
                    Crear Nuevo Usuario
                </h1>
                <p class="profile-subtitle">Complete la información del nuevo usuario del sistema</p>
            </div>
        </div>

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Sección: Foto de Perfil -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-camera"></i>
                    <h3>Foto de Perfil</h3>
                </div>
                <div class="section-content">
                    <div class="photo-upload-container">
                        <div class="photo-preview-wrapper">
                            <img id="photoPreview" 
                                 src="{{ asset('storage/photos/fotousuario.png') }}" 
                                 alt="Vista previa" 
                                 class="photo-preview">
                            <div class="photo-overlay">
                                <i class="fas fa-camera"></i>
                                <span>Cambiar foto</span>
                            </div>
                        </div>
                        <div class="photo-upload-info">
                            <label for="photoInput" class="btn-upload">
                                <i class="fas fa-upload"></i> Seleccionar Foto
                            </label>
                            <input type="file" 
                                   name="photo" 
                                   id="photoInput" 
                                   accept="image/*" 
                                   onchange="previewPhoto(event)" 
                                   style="display: none;">
                            <p class="upload-hint">JPG, PNG o GIF. Tamaño máximo 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección: Datos Personales -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-user"></i>
                    <h3>Datos Personales</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Nombre Completo
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control" 
                                   value="{{ old('name') }}" 
                                   placeholder="Ej: Juan Pérez"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Correo Electrónico
                            </label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   class="form-control" 
                                   value="{{ old('email') }}" 
                                   placeholder="correo@ejemplo.com"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="dni" class="form-label">
                                <i class="fas fa-id-card"></i> DNI
                            </label>
                            <input type="text" 
                                   name="dni" 
                                   id="dni" 
                                   class="form-control" 
                                   value="{{ old('dni') }}" 
                                   placeholder="Ej: 12345678">
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i> Teléfono
                            </label>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   class="form-control" 
                                   value="{{ old('phone') }}" 
                                   placeholder="Ej: +54 9 11 1234-5678">
                        </div>

                        <div class="form-group full-width">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Dirección
                            </label>
                            <input type="text" 
                                   name="address" 
                                   id="address" 
                                   class="form-control" 
                                   value="{{ old('address') }}" 
                                   placeholder="Ej: Av. Corrientes 1234, CABA">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección: Seguridad y Rol -->
            <div class="form-section">
                <div class="section-header">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Seguridad y Permisos</h3>
                </div>
                <div class="section-content">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Contraseña
                            </label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="form-control" 
                                   placeholder="Mínimo 8 caracteres"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="roles_id" class="form-label">
                                <i class="fas fa-user-tag"></i> Rol del Usuario
                            </label>
                            <select name="roles_id" id="roles_id" class="form-control" required>
                                <option value="">Seleccione un rol...</option>
                                @foreach($roles as $rol)
                                    @if(!(Auth::user()->roles_id == 3 && $rol->id == 4))
                                        <option value="{{ $rol->id }}" {{ old('roles_id') == $rol->id ? 'selected' : '' }}>
                                            {{ $rol->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Crear Usuario
                </button>
                <a href="{{ route('users.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
