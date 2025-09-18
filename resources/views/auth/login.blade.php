@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Iniciar Sesión</h2>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Contraseña</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Entrar</button>
    </form>
</div>
@endsection
