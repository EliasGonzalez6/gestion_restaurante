@extends('layouts.app')

@section('title', 'Mi Perfil - Re Chévere Digital')

@section('content')
<!-- Link al CSS del perfil -->
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="profile-container">
    <div class="container">
        <!-- Título principal -->
        <div class="profile-header-section">
            <h1 class="profile-title">Mi Perfil</h1>
            <p class="profile-subtitle">Administra tu información personal</p>
        </div>

        <!-- Bloque principal del perfil -->
        <div class="profile-main-block">
            <!-- Gradiente superior -->
            <div class="profile-header-gradient"></div>

            <!-- Foto de perfil -->
            <div class="profile-photo-container">
                <div class="profile-photo">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto de perfil" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                <div class="profile-badge">⭐</div>
            </div>

            <!-- Información del usuario -->
            <div class="profile-user-info">
                <h2 class="profile-user-name">{{ $user->name }}</h2>
                <span class="profile-user-role">
                    {{ $user->rol ? $user->rol->name : 'Usuario' }}
                </span>
            </div>

            <!-- Tarjetas de información -->
            <div class="profile-info-cards">
                <div class="row">
                    <!-- Tarjeta Email -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="profile-card">
                            <div class="profile-card-icon-container">
                                <i class="fas fa-envelope profile-card-icon"></i>
                            </div>
                            <div class="profile-card-info">
                                <div class="profile-card-title">Email</div>
                                <div class="profile-card-content">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta Teléfono -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="profile-card">
                            <div class="profile-card-icon-container">
                                <i class="fas fa-phone profile-card-icon"></i>
                            </div>
                            <div class="profile-card-info">
                                <div class="profile-card-title">Teléfono</div>
                                <div class="profile-card-content">
                                    {{ $user->phone ? $user->phone : 'No registrado' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta DNI -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="profile-card">
                            <div class="profile-card-icon-container">
                                <i class="fas fa-id-card profile-card-icon"></i>
                            </div>
                            <div class="profile-card-info">
                                <div class="profile-card-title">DNI</div>
                                <div class="profile-card-content">
                                    {{ $user->dni ? $user->dni : 'No registrado' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta Dirección -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="profile-card">
                            <div class="profile-card-icon-container">
                                <i class="fas fa-map-marker-alt profile-card-icon"></i>
                            </div>
                            <div class="profile-card-info">
                                <div class="profile-card-title">Dirección</div>
                                <div class="profile-card-content">
                                    {{ $user->address ? $user->address : 'No registrado' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta Fecha de registro -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="profile-card">
                            <div class="profile-card-icon-container">
                                <i class="fas fa-calendar-alt profile-card-icon"></i>
                            </div>
                            <div class="profile-card-info">
                                <div class="profile-card-title">Miembro desde</div>
                                <div class="profile-card-content">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta Estado -->
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="profile-card">
                            <div class="profile-card-icon-container">
                                <i class="fas fa-check-circle profile-card-icon"></i>
                            </div>
                            <div class="profile-card-info">
                                <div class="profile-card-title">Estado de la cuenta</div>
                                <div class="profile-card-content">Activa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botón editar perfil -->
            <div class="profile-edit-btn-container">
                <a href="{{ route('profile.edit') }}" class="profile-edit-btn">
                    <i class="fas fa-edit"></i> Editar Perfil
                </a>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
@endsection
