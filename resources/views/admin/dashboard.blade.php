@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">

<!-- Sidebar -->
<div class="sidebar">
    <ul class="sidebar-menu">
        @if(Auth::user()->roles_id == 3 || Auth::user()->roles_id == 4)
        <li>
            <a href="{{ route('users.index') }}" class="menu-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Gestión de Usuarios</span>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('admin.menu.index') }}" class="menu-link {{ request()->routeIs('admin.menu.index') ? 'active' : '' }}">
                <i class="fas fa-utensils"></i>
                <span>Gestión de Menú</span>
            </a>
        </li>
        <li>
            <a href="#" class="menu-link disabled">
                <i class="fas fa-calendar-alt"></i>
                <span>Reservas</span>
            </a>
        </li>
        <li>
            <a href="#" class="menu-link disabled">
                <i class="fas fa-shopping-cart"></i>
                <span>Pedidos</span>
            </a>
        </li>
    </ul>
</div>

<!-- Overlay para móvil -->
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<!-- Contenido principal -->
@yield('admin_content')

<script>
// Toggle sidebar en móvil/tablet
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

// Cerrar sidebar al hacer clic en un link (solo en móvil)
document.addEventListener('DOMContentLoaded', function() {
    const menuLinks = document.querySelectorAll('.sidebar-menu .menu-link');
    
    menuLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Solo cerrar en móvil
            if (window.innerWidth <= 992) {
                const sidebar = document.querySelector('.sidebar');
                const overlay = document.querySelector('.sidebar-overlay');
                
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }
        });
    });
});
</script>
@endsection
