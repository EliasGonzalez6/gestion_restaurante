@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 bg-light vh-100 p-0">
            <div class="d-flex flex-column py-4 px-3 h-100">
                <h5 class="mb-4">Panel de Administración</h5>
                <ul class="nav flex-column">
                    @if(Auth::user()->roles_id == 3 || Auth::user()->roles_id == 4)
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center" href="{{ route('users.index') }}">
                            <i class="bi bi-people me-2"></i> Gestión de Usuarios
                        </a>
                    </li>
                    @endif
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.menu.index') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}">
                            <i class="bi bi-list-ul me-2"></i> Gestión de Menú
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center disabled" href="#">
                            <i class="bi bi-calendar-check me-2"></i> Reservas
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link d-flex align-items-center disabled" href="#">
                            <i class="bi bi-basket me-2"></i> Pedidos
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-md-9 p-4">
            @yield('admin_content')
        </div>
    </div>
</div>
@endsection
