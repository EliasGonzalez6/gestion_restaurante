@extends('layouts.app')

@section('content')
<link href="{{ asset('css/reservations.css') }}" rel="stylesheet">

@include('partials.navbar')

<div class="reservation-container" style="margin-top: -35px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="reservation-card">
                    <div class="card-header text-center">
                        <h2 class="mb-2"><i class="fas fa-calendar-check"></i> Hacer una Reserva</h2>
                        <p class="text-white mt-3">Reserva tu mesa en Re Chévere y disfruta de una experiencia única</p>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('reservations.store') }}" method="POST">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="reservation_date" class="form-label">
                                        <i class="fas fa-calendar"></i> Fecha de Reserva *
                                    </label>
                                    <input type="date" 
                                           class="form-control @error('reservation_date') is-invalid @enderror" 
                                           id="reservation_date" 
                                           name="reservation_date" 
                                           value="{{ old('reservation_date') }}"
                                           min="{{ date('Y-m-d') }}"
                                           required>
                                    @error('reservation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="reservation_time" class="form-label">
                                        <i class="fas fa-clock"></i> Hora de Reserva *
                                    </label>
                                    <input type="time" 
                                           class="form-control @error('reservation_time') is-invalid @enderror" 
                                           id="reservation_time" 
                                           name="reservation_time" 
                                           value="{{ old('reservation_time') }}"
                                           min="12:00"
                                           max="22:30"
                                           required>
                                    <small class="text-muted">Horario disponible: 12:00 PM - 10:30 PM</small>
                                    @error('reservation_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="number_of_people" class="form-label">
                                    <i class="fas fa-users"></i> Cantidad de Personas *
                                </label>
                                <input type="number" 
                                       class="form-control @error('number_of_people') is-invalid @enderror" 
                                       id="number_of_people" 
                                       name="number_of_people" 
                                       value="{{ old('number_of_people', 2) }}"
                                       min="1" 
                                       max="20"
                                       required>
                                <small class="text-muted">Máximo 20 personas por reserva</small>
                                @error('number_of_people')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="observations" class="form-label">
                                    <i class="fas fa-comment-dots"></i> Observaciones (Opcional)
                                </label>
                                <textarea class="form-control @error('observations') is-invalid @enderror" 
                                          id="observations" 
                                          name="observations" 
                                          rows="4"
                                          maxlength="500"
                                          placeholder="Menciona preferencias, alergias, ocasiones especiales, etc.">{{ old('observations') }}</textarea>
                                <small class="text-muted">Máximo 500 caracteres</small>
                                @error('observations')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert" style="background: #f5f1e8; color: #7d1935; border-left: 4px solid #d4af37; padding: 15px 20px; border-radius: 12px; display: flex; align-items: center; gap: 15px; font-size: 1.04rem;">
                                <i class="fas fa-info-circle" style="font-size: 1.5rem; flex-shrink: 0;"></i> 
                                <div><strong style="font-weight: 700; margin-right: 5px;">Importante:</strong> Podrás cancelar tu reserva hasta 1 día antes de la fecha programada desde tu perfil.</div>
                            </div>

                            <div class="row g-2">
                                <div class="col-12 col-lg-6">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-paper-plane"></i> Solicitar Reserva
                                    </button>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-lg w-100">
                                        <i class="fas fa-arrow-left"></i> Volver al Inicio
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
