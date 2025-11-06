@extends('layouts.app')

@section('content')
<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Estilos personalizados -->
<link href="{{ asset('css/login.css') }}" rel="stylesheet">
<!-- Fuente e íconos -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="container">
    <h2>Iniciar Sesión</h2>
    <p class="welcome-text text-muted">Bienvenido de vuelta. Inicia sesión para continuar disfrutando la experiencia</p>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label><i class="fa fa-envelope"></i> Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="tu@ejemplo.com">
        </div>

        <div class="mb-3">
            <label><i class="fa fa-lock"></i> Contraseña</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="fa fa-exclamation-triangle"></i> {{ $errors->first() }}
            </div>
        @endif

        <button type="submit" class="btn btn-success">Entrar</button>
    </form>
</div>
@endsection
