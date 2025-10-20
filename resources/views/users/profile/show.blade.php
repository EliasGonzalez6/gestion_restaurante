@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Mi Perfil</h1>
    <div class="card" style="max-width: 500px;">
        <div class="card-body">
            <div class="text-center mb-3">
                @if($user->photo)
                    <img src="{{ asset('storage/'.$user->photo) }}" class="rounded-circle" width="120" height="120" style="object-fit:cover;">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" class="rounded-circle" width="120" height="120">
                @endif
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nombre:</strong> {{ $user->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                <li class="list-group-item"><strong>DNI:</strong> {{ $user->dni }}</li>
                <li class="list-group-item"><strong>Teléfono:</strong> {{ $user->phone }}</li>
                <li class="list-group-item"><strong>Dirección:</strong> {{ $user->address }}</li>
            <div class="mt-3 text-center">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">Editar perfil</a>
            </div>
            </ul>
        </div>
    </div>
</div>

@include('partials.footer')
@endsection
