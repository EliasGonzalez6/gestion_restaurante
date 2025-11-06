@extends('admin.dashboard')

@section('admin_content')
<link rel="stylesheet" href="{{ asset('css/admin-reservations.css') }}">
<link rel="stylesheet" href="{{ asset('css/delete-modal.css') }}">

<div class="main-content">
    <!-- HEADER DE PÁGINA -->
    <div class="page-header">
        <h1><i class="fas fa-calendar-check"></i> Gestión de Reservas</h1>
        <p>Administra las reservas de los clientes del restaurante</p>
    </div>

    <!-- SECCIÓN RESERVAS PENDIENTES -->
    <div class="admin-card">
        <h3 class="card-title">
            <i class="fas fa-clock"></i> Reservas Pendientes
            <span class="badge-count">{{ $pendingReservations->count() }}</span>
        </h3>

        @if($pendingReservations->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-clock"></i> Hora</th>
                            <th><i class="fas fa-users"></i> Personas</th>
                            <th><i class="fas fa-comment"></i> Observaciones</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingReservations as $reservation)
                        <tr>
                            <td><span class="id-badge">#{{ $reservation->id }}</span></td>
                            <td>
                                <div class="client-info">
                                    <i class="fas fa-user-circle"></i>
                                    <div>
                                        <div class="client-name">{{ $reservation->user->name }}</div>
                                        <div class="client-email">{{ $reservation->user->email }}</div>
                                        @if($reservation->user->phone)
                                            <div class="client-phone"><i class="fas fa-phone"></i> {{ $reservation->user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="date-badge">
                                    <i class="fas fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="time-badge">
                                    <i class="fas fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <span class="people-badge">
                                    <i class="fas fa-users"></i>
                                    {{ $reservation->number_of_people }}
                                </span>
                            </td>
                            <td>
                                @if($reservation->observations)
                                    <div class="observations-cell" title="{{ $reservation->observations }}">
                                        {{ Str::limit($reservation->observations, 40) }}
                                    </div>
                                @else
                                    <span class="text-muted">Sin observaciones</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form action="{{ route('admin.reservations.accept', $reservation) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-accept" title="Aceptar reserva">
                                            <i class="fas fa-check"></i> Aceptar
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reservations.reject', $reservation) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-reject" title="Rechazar reserva" onclick="return confirm('¿Estás seguro de rechazar esta reserva?')">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state-admin">
                <i class="fas fa-calendar-check"></i>
                <p>No hay reservas pendientes en este momento</p>
            </div>
        @endif
    </div>

    <!-- SECCIÓN RESERVAS ACEPTADAS -->
    <div class="admin-card">
        <h3 class="card-title">
            <i class="fas fa-check-circle"></i> Reservas Aceptadas
            <span class="badge-count badge-success">{{ $acceptedReservations->count() }}</span>
        </h3>

        @if($acceptedReservations->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-clock"></i> Hora</th>
                            <th><i class="fas fa-users"></i> Personas</th>
                            <th><i class="fas fa-user-tie"></i> Gestionado por</th>
                            <th><i class="fas fa-info-circle"></i> Estado</th>
                            <th><i class="fas fa-cog"></i> Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($acceptedReservations as $reservation)
                        <tr>
                            <td><span class="id-badge">#{{ $reservation->id }}</span></td>
                            <td>
                                <div class="client-info">
                                    <i class="fas fa-user-circle"></i>
                                    <div>
                                        <div class="client-name">{{ $reservation->user->name }}</div>
                                        <div class="client-email">{{ $reservation->user->email }}</div>
                                        @if($reservation->user->phone)
                                            <div class="client-phone"><i class="fas fa-phone"></i> {{ $reservation->user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="date-badge">
                                    <i class="fas fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="time-badge">
                                    <i class="fas fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <span class="people-badge">
                                    <i class="fas fa-users"></i>
                                    {{ $reservation->number_of_people }}
                                </span>
                            </td>
                            <td>
                                @if($reservation->manager)
                                    <span class="manager-badge">
                                        <i class="fas fa-user-shield"></i>
                                        {{ $reservation->manager->name }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-accepted">
                                    <i class="fas fa-check-circle"></i> Aceptada
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form action="{{ route('admin.reservations.markCompleted', $reservation) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-mark-complete" title="Marcar como completada">
                                            <i class="fas fa-check-double"></i> Completada
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.reservations.markNotCompleted', $reservation) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-mark-incomplete" title="Marcar como no completada">
                                            <i class="fas fa-user-times"></i> No Completada
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state-admin">
                <i class="fas fa-check-circle"></i>
                <p>No hay reservas aceptadas en este momento</p>
            </div>
        @endif
    </div>

    <!-- SECCIÓN OTRAS RESERVAS (Rechazadas, Canceladas y Completadas) -->
    <div class="admin-card">
        <h3 class="card-title">
            <i class="fas fa-archive"></i> Historial de Reservas (Completadas, Rechazadas y Canceladas)
            <span class="badge-count badge-secondary">{{ $otherReservations->count() }}</span>
        </h3>

        <!-- Filtros y Buscador -->
        <div class="filter-section mb-4">
            <form method="GET" action="{{ route('admin.reservations.index') }}" class="filter-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="other_filter" class="filter-label">
                            <i class="fas fa-filter"></i> Filtrar:
                        </label>
                        <select name="other_filter" id="other_filter" class="filter-select" onchange="this.form.submit()">
                            <option value="todas" {{ $otherFilter === 'todas' ? 'selected' : '' }}>Todas</option>
                            <option value="completadas" {{ $otherFilter === 'completadas' ? 'selected' : '' }}>Completadas</option>
                            <option value="rechazadas" {{ $otherFilter === 'rechazadas' ? 'selected' : '' }}>Rechazadas</option>
                            <option value="canceladas" {{ $otherFilter === 'canceladas' ? 'selected' : '' }}>Canceladas</option>
                            <option value="no_completadas" {{ $otherFilter === 'no_completadas' ? 'selected' : '' }}>No Completadas</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="search_name" class="filter-label">
                            <i class="fas fa-search"></i> Buscar Cliente:
                        </label>
                        <input type="text" 
                               name="search_name" 
                               id="search_name" 
                               class="search-input" 
                               placeholder="Nombre o email del cliente"
                               value="{{ $searchName }}">
                        <button type="submit" class="btn-search">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        @if($searchName)
                            <a href="{{ route('admin.reservations.index', ['other_filter' => $otherFilter]) }}" class="btn-clear">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if($otherReservations->count() > 0)
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Cliente</th>
                            <th><i class="fas fa-calendar-alt"></i> Fecha</th>
                            <th><i class="fas fa-clock"></i> Hora</th>
                            <th><i class="fas fa-users"></i> Personas</th>
                            <th><i class="fas fa-info-circle"></i> Estado</th>
                            <th><i class="fas fa-calendar-times"></i> Cancelada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($otherReservations as $reservation)
                        <tr class="row-inactive">
                            <td><span class="id-badge">#{{ $reservation->id }}</span></td>
                            <td>
                                <div class="client-info">
                                    <i class="fas fa-user-circle"></i>
                                    <div>
                                        <div class="client-name">{{ $reservation->user->name }}</div>
                                        <div class="client-email">{{ $reservation->user->email }}</div>
                                        @if($reservation->user->phone)
                                            <div class="client-phone"><i class="fas fa-phone"></i> {{ $reservation->user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="date-badge">
                                    <i class="fas fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td>
                                <span class="time-badge">
                                    <i class="fas fa-clock"></i>
                                    {{ \Carbon\Carbon::parse($reservation->reservation_time)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                <span class="people-badge">
                                    <i class="fas fa-users"></i>
                                    {{ $reservation->number_of_people }}
                                </span>
                            </td>
                            <td>
                                @if($reservation->status === 'rechazada')
                                    <span class="status-badge status-rejected">
                                        <i class="fas fa-times-circle"></i> Rechazada
                                    </span>
                                @elseif($reservation->status === 'cancelada')
                                    <span class="status-badge status-canceled">
                                        <i class="fas fa-ban"></i> Cancelada
                                    </span>
                                @elseif($reservation->completion_status === 'completada')
                                    <span class="status-badge status-completed">
                                        <i class="fas fa-check-double"></i> Completada
                                    </span>
                                @elseif($reservation->completion_status === 'no_completada')
                                    <span class="status-badge status-not-completed">
                                        <i class="fas fa-exclamation-triangle"></i> No Completada
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($reservation->canceled_at)
                                    <span class="canceled-date">
                                        {{ \Carbon\Carbon::parse($reservation->canceled_at)->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state-admin">
                <i class="fas fa-archive"></i>
                <p>No hay reservas rechazadas o canceladas</p>
            </div>
        @endif
    </div>
</div>

@endsection
