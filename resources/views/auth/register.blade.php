@extends('layouts.app')

@section('content')
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Estilos personalizados -->
<link href="{{ asset('css/register.css') }}" rel="stylesheet">
<!-- Fuente e íconos -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="register-container mt-4">
    <h2 class="text-center mb-1">Re Chévere Digital</h2>
    <p class="text-center text-muted mb-4">Crea tu cuenta y disfruta de una experiencia gastronómica única</p>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf

        <!-- Nombre completo -->
        <div class="mb-3">
            <label for="name" class="form-label"><i class="fa fa-user"></i> Nombre completo</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Ej. Juan Pérez">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Correo y teléfono -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label"><i class="fa fa-envelope"></i> Correo electrónico</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="tu@ejemplo.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="phone" class="form-label"><i class="fa fa-phone"></i> Teléfono</label>
                <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="+54 11 1234-5678" pattern="[\+]?[0-9\s\-()]{8,20}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- DNI y dirección -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="dni" class="form-label"><i class="fa fa-id-card"></i> DNI</label>
                <input id="dni" type="text" class="form-control @error('dni') is-invalid @enderror" name="dni" value="{{ old('dni') }}" required placeholder="12345678" pattern="[0-9]{7,10}" title="Ingrese un DNI válido (7-10 dígitos)">
                @error('dni')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label"><i class="fa fa-map-marker-alt"></i> Dirección</label>
                <input id="address" type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address') }}" required placeholder="Calle 123, Ciudad">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Contraseña y confirmar contraseña -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label"><i class="fa fa-lock"></i> Contraseña</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Mínimo 6 caracteres" autocomplete="new-password" minlength="6">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label"><i class="fa fa-lock"></i> Confirmar contraseña</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Repite tu contraseña" autocomplete="new-password" minlength="6">
            </div>
        </div>

        <!-- Foto -->
        <div class="mb-3">
            <label for="photo" class="form-label"><i class="fa fa-camera"></i> Foto de perfil (Opcional)</label>
            <input id="photo" type="file" class="form-control @error('photo') is-invalid @enderror" name="photo" accept="image/jpeg,image/jpg,image/png">
            @error('photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">Si no subes una foto, usaremos una imagen predeterminada.</small>
            <div id="photo-preview" class="mt-2"></div>
        </div>

        <!-- Botón -->
        <button type="submit" class="btn-register">
            <i class="fa fa-check-circle"></i> Registrarse
        </button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del formulario
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const phoneInput = document.getElementById('phone');
    const dniInput = document.getElementById('dni');
    const addressInput = document.getElementById('address');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const photoInput = document.getElementById('photo');
    const form = document.querySelector('form');

    // Validación en tiempo real para nombre
    nameInput.addEventListener('input', function() {
        const regex = /^[\pL\s\-]+$/u;
        if (this.value.length > 0 && this.value.length < 3) {
            this.setCustomValidity('El nombre debe tener al menos 3 caracteres');
        } else if (!regex.test(this.value) && this.value.length > 0) {
            this.setCustomValidity('El nombre solo puede contener letras y espacios');
        } else {
            this.setCustomValidity('');
        }
    });

    // Validación para DNI (solo números)
    dniInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 0 && (this.value.length < 7 || this.value.length > 10)) {
            this.setCustomValidity('El DNI debe tener entre 7 y 10 dígitos');
        } else {
            this.setCustomValidity('');
        }
    });

    // Validación para teléfono
    phoneInput.addEventListener('input', function() {
        const regex = /^[\+]?[0-9\s\-()]+$/;
        if (this.value.length > 0 && !regex.test(this.value)) {
            this.setCustomValidity('Formato de teléfono inválido. Use solo números, +, -, ( ) y espacios');
        } else if (this.value.replace(/[^0-9]/g, '').length < 8) {
            this.setCustomValidity('El teléfono debe tener al menos 8 dígitos');
        } else {
            this.setCustomValidity('');
        }
    });

    // Validación de confirmación de contraseña
    passwordConfirmInput.addEventListener('input', function() {
        if (this.value !== passwordInput.value) {
            this.setCustomValidity('Las contraseñas no coinciden');
        } else {
            this.setCustomValidity('');
        }
    });

    // También validar cuando se cambia la contraseña principal
    passwordInput.addEventListener('input', function() {
        if (passwordConfirmInput.value.length > 0) {
            if (passwordConfirmInput.value !== this.value) {
                passwordConfirmInput.setCustomValidity('Las contraseñas no coinciden');
            } else {
                passwordConfirmInput.setCustomValidity('');
            }
        }
    });

    // Preview de imagen
    photoInput.addEventListener('change', function(e) {
        const previewDiv = document.getElementById('photo-preview');
        const file = e.target.files[0];
        
        // Si no hay archivo, limpiar preview y no marcar error (ya que es opcional)
        if (!file) {
            previewDiv.innerHTML = '';
            this.setCustomValidity('');
            return;
        }
        
        // Validar tamaño (10MB = 10240KB)
        if (file.size > 10240 * 1024) {
            this.setCustomValidity('La imagen no puede superar los 10MB');
            previewDiv.innerHTML = '<div class="alert alert-danger mt-2">La imagen es demasiado grande. Máximo 10MB.</div>';
            return;
        }

        // Validar tipo
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            this.setCustomValidity('Solo se permiten imágenes JPG, JPEG o PNG');
            previewDiv.innerHTML = '<div class="alert alert-danger mt-2">Formato inválido. Use JPG, JPEG o PNG.</div>';
            return;
        }

        this.setCustomValidity('');

        // Crear preview
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = new Image();
            img.onload = function() {
                // Validar dimensiones
                if (this.width < 100 || this.height < 100) {
                    photoInput.setCustomValidity('La imagen debe tener al menos 100x100 píxeles');
                    previewDiv.innerHTML = '<div class="alert alert-danger mt-2">La imagen es demasiado pequeña (mínimo 100x100px).</div>';
                } else if (this.width > 4000 || this.height > 4000) {
                    photoInput.setCustomValidity('La imagen no puede superar 4000x4000 píxeles');
                    previewDiv.innerHTML = '<div class="alert alert-danger mt-2">La imagen es demasiado grande (máximo 4000x4000px).</div>';
                } else {
                    photoInput.setCustomValidity('');
                    previewDiv.innerHTML = `
                        <div class="text-center">
                            <img src="${e.target.result}" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 10px; border: 2px solid #ddd;">
                            <p class="text-muted mt-1"><small>${this.width}x${this.height}px - ${(file.size / 1024).toFixed(2)}KB</small></p>
                        </div>
                    `;
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });

    // Validación general del formulario antes de enviar
    form.addEventListener('submit', function(e) {
        // Verificar si todos los campos son válidos
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Convertir email a minúsculas automáticamente
    emailInput.addEventListener('blur', function() {
        this.value = this.value.toLowerCase();
    });
});
</script>
@endsection
