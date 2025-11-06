@extends('layouts.app')

@section('content')
<link href="{{ asset('css/reservations.css') }}" rel="stylesheet">

@include('partials.navbar')

<div class="reservation-container">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="reservation-welcome-card">
                    <div class="text-center mb-4">
                        <i class="fas fa-calendar-check reservation-icon"></i>
                        <h1 class="display-4 fw-bold mb-3">Reserva tu Mesa</h1>
                        <p class="lead text-muted">
                            Asegura tu lugar en Re Chévere y disfruta de una experiencia gastronómica inolvidable
                        </p>
                    </div>

                    <div class="reservation-benefits mb-5">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="benefit-card">
                                    <i class="fas fa-utensils benefit-icon"></i>
                                    <h5>Menú Exclusivo</h5>
                                    <p class="text-muted small">Sabores auténticos venezolanos</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="benefit-card">
                                    <i class="fas fa-clock benefit-icon"></i>
                                    <h5>Sin Esperas</h5>
                                    <p class="text-muted small">Tu mesa lista cuando llegues</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="benefit-card">
                                    <i class="fas fa-star benefit-icon"></i>
                                    <h5>Atención Premium</h5>
                                    <p class="text-muted small">Servicio personalizado</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="auth-required-box">
                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div>
                                <h5 class="alert-heading mb-1">Necesitas una cuenta para reservar</h5>
                                <p class="mb-0">Inicia sesión o regístrate para poder hacer tu reserva. ¡Es rápido y fácil!</p>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Iniciar Sesión
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Registrarse
                                </a>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('welcome') }}" class="btn btn-link text-muted">
                                <i class="fas fa-arrow-left me-2"></i>
                                Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')
@endsection
