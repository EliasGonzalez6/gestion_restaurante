@extends('layouts.app')

@section('title', 'Mi Perfil - Re Chévere Digital')

@section('content')
<!-- Link al CSS del perfil -->
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/delete-modal.css') }}">
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
                    @php
                        $photoUrl = $user->photo 
                            ? asset('storage/' . $user->photo) 
                            : asset('storage/photos/fotousuario.png');
                    @endphp
                    <img src="{{ $photoUrl }}" alt="Foto de perfil" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
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

        <!-- Tarjeta de Reservas (Sin gradiente, todo centrado) -->
        <div class="profile-main-block reservations-block mt-4">
            <!-- Título de la sección -->
            <div class="reservations-section-header text-center">
                <h2 class="reservations-main-title">
                    <i class="fas fa-calendar-check me-2"></i>
                    Mis Reservas
                </h2>
                <p class="reservations-main-subtitle">Administra tus reservas y consulta su estado</p>
            </div>
                
            @php
                $userReservations = Auth::user()->reservations()->latest()->get();
            @endphp

            @if($userReservations->count() > 0)
                <!-- Grid de reservas usando el mismo estilo de las tarjetas de información -->
                <div class="profile-info-cards">
                    <div class="row">
                        @foreach($userReservations as $reservation)
                            <div class="col-12">
                                <div class="profile-card reservation-card-item mb-3">
                                    <div class="reservation-card-content">
                                        <!-- Fecha y Hora -->
                                        <div class="reservation-info-item">
                                            <i class="fas fa-calendar-alt reservation-icon"></i>
                                            <div class="reservation-details">
                                                <div class="reservation-label">Fecha y Hora</div>
                                                <div class="reservation-value">
                                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }} 
                                                    a las {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Cantidad de Personas -->
                                        <div class="reservation-info-item">
                                            <i class="fas fa-users reservation-icon"></i>
                                            <div class="reservation-details">
                                                <div class="reservation-label">Personas</div>
                                                <div class="reservation-value">
                                                    {{ $reservation->number_of_people }} {{ $reservation->number_of_people == 1 ? 'persona' : 'personas' }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Observaciones (si existen) -->
                                        @if($reservation->observations)
                                            <div class="reservation-info-item reservation-observations-item">
                                                <i class="fas fa-comment-dots reservation-icon"></i>
                                                <div class="reservation-details">
                                                    <div class="reservation-label">Observaciones</div>
                                                    <div class="reservation-value">{{ $reservation->observations }}</div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Estado -->
                                        <div class="reservation-info-item">
                                            <i class="fas fa-info-circle reservation-icon"></i>
                                            <div class="reservation-details">
                                                <div class="reservation-label">Estado</div>
                                                <span class="reservation-badge-status badge-{{ $reservation->status_color }}">
                                                    {{ $reservation->status_name }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Acciones -->
                                        @if($reservation->canBeCanceled())
                                            <div class="reservation-info-item reservation-actions-item">
                                                <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" class="reservation-cancel-form" id="cancel-form-{{ $reservation->id }}">
                                                    @csrf
                                                    <button type="button" class="reservation-cancel-button" onclick="showCancelModal({{ $reservation->id }}, '{{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}')">
                                                        <i class="fas fa-times me-1"></i> Cancelar Reserva
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Sin reservas - usando el estilo de las tarjetas -->
                <div class="profile-info-cards">
                    <div class="profile-card">
                        <div class="empty-state">
                            <i class="fas fa-calendar-times empty-state-icon"></i>
                            <h4 class="empty-state-title">No tienes reservas activas</h4>
                            <p class="empty-state-text">¡Reserva tu mesa y disfruta de una experiencia única!</p>
                            <a href="{{ route('reservations.index') }}" class="empty-state-button">
                                <i class="fas fa-plus me-2"></i>Hacer una Reserva
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de Confirmación de Cancelación -->
<div class="delete-modal-overlay" id="cancelModal">
    <div class="delete-modal">
        <div class="delete-modal-header">
            <div class="delete-modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2 class="delete-modal-title">Confirmar Cancelación</h2>
        </div>
        <div class="delete-modal-body">
            <p class="delete-modal-message">¿Está seguro de que desea cancelar esta reserva?</p>
            <p class="delete-modal-item" id="cancelItemDate"></p>
            <p class="delete-modal-warning">
                <i class="fas fa-info-circle"></i> Esta acción no se puede deshacer
            </p>
        </div>
        <div class="delete-modal-footer">
            <button type="button" class="modal-btn modal-btn-cancel" onclick="hideCancelModal()">
                <i class="fas fa-times"></i> Cancelar
            </button>
            <button type="button" class="modal-btn modal-btn-delete" onclick="confirmCancel()">
                <i class="fas fa-ban"></i> Cancelar Reserva
            </button>
        </div>
    </div>
</div>

<script>
let currentCancelFormId = null;

function showCancelModal(reservationId, reservationDate) {
    currentCancelFormId = 'cancel-form-' + reservationId;
    document.getElementById('cancelItemDate').textContent = reservationDate;
    document.getElementById('cancelModal').classList.add('show');
}

function hideCancelModal() {
    document.getElementById('cancelModal').classList.remove('show');
    currentCancelFormId = null;
}

function confirmCancel() {
    if (currentCancelFormId) {
        document.getElementById(currentCancelFormId).submit();
    }
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('cancelModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideCancelModal();
    }
});
</script>

@include('partials.footer')
@endsection
