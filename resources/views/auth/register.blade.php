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
            <input id="name" type="text" class="form-control" name="name" required autofocus placeholder="Ej. Juan Pérez">
        </div>

        <!-- Correo y teléfono -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="email" class="form-label"><i class="fa fa-envelope"></i> Correo electrónico</label>
                <input id="email" type="email" class="form-control" name="email" required placeholder="tu@ejemplo.com">
            </div>
            <div class="col-md-6">
                <label for="telefono" class="form-label"><i class="fa fa-phone"></i> Teléfono</label>
                <input id="telefono" type="text" class="form-control" name="telefono" placeholder="+54 11 1234-5678">
            </div>
        </div>

        <!-- DNI y dirección -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="dni" class="form-label"><i class="fa fa-id-card"></i> DNI</label>
                <input id="dni" type="text" class="form-control" name="dni" placeholder="12345678">
            </div>
            <div class="col-md-6">
                <label for="direccion" class="form-label"><i class="fa fa-map-marker-alt"></i> Dirección</label>
                <input id="direccion" type="text" class="form-control" name="direccion" placeholder="Calle 123, Ciudad">
            </div>
        </div>

        <!-- Contraseña y confirmar contraseña -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="password" class="form-label"><i class="fa fa-lock"></i> Contraseña</label>
                <input id="password" type="password" class="form-control" name="password" required placeholder="Mínimo 8 caracteres" autocomplete="new-password">
            </div>
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label"><i class="fa fa-lock"></i> Confirmar contraseña</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Repite tu contraseña" autocomplete="new-password">
            </div>
        </div>

        <!-- Foto -->
        <div class="mb-3">
            <label for="photo" class="form-label"><i class="fa fa-camera"></i> Foto de perfil</label>
            <input id="photo" type="file" class="form-control" name="photo" accept="image/*">
        </div>

        <!-- Botón -->
        <button type="submit" class="btn-register">
            <i class="fa fa-check-circle"></i> Registrarse
        </button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
