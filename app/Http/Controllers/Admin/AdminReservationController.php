<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReservationController extends Controller
{
    // Vista principal con todas las reservas organizadas
    public function index(Request $request)
    {
        // Reservas pendientes (sin filtros)
        $pendingReservations = Reservation::pending()
            ->with('user')
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->get();

        // Reservas aceptadas (sin filtros, solo las que NO están completadas)
        $acceptedReservations = Reservation::accepted()
            ->where('completion_status', 'pendiente')
            ->with('user', 'manager')
            ->orderBy('reservation_date', 'asc')
            ->orderBy('reservation_time', 'asc')
            ->get();

        // Filtros para historial de reservas (rechazadas, canceladas o completadas)
        $otherFilter = $request->get('other_filter', 'todas');
        $searchName = $request->get('search_name', '');
        
        $otherReservations = Reservation::where(function($query) {
                $query->whereIn('status', ['rechazada', 'cancelada'])
                      ->orWhere('completion_status', '!=', 'pendiente');
            })
            ->with('user', 'manager')
            ->when($otherFilter === 'completadas', function($query) {
                return $query->where('completion_status', 'completada');
            })
            ->when($otherFilter === 'rechazadas', function($query) {
                return $query->where('status', 'rechazada');
            })
            ->when($otherFilter === 'canceladas', function($query) {
                return $query->where('status', 'cancelada');
            })
            ->when($otherFilter === 'no_completadas', function($query) {
                return $query->where('completion_status', 'no_completada');
            })
            ->when($searchName, function($query) use ($searchName) {
                return $query->whereHas('user', function($q) use ($searchName) {
                    $q->where('name', 'like', '%' . $searchName . '%')
                      ->orWhere('email', 'like', '%' . $searchName . '%');
                });
            })
            ->latest()
            ->get();

        return view('admin.reservations.admin-reservations', compact(
            'pendingReservations', 
            'acceptedReservations', 
            'otherReservations',
            'otherFilter',
            'searchName'
        ));
    }

    // Aceptar una reserva
    public function accept(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'aceptada',
            'managed_by' => Auth::id()
        ]);

        return back()->with('success', 'Reserva aceptada exitosamente.');
    }

    // Rechazar una reserva
    public function reject(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'rechazada',
            'managed_by' => Auth::id()
        ]);

        return back()->with('success', 'Reserva rechazada.');
    }

    // Marcar como completada
    public function markCompleted(Reservation $reservation)
    {
        $reservation->update([
            'completion_status' => 'completada',
            'managed_by' => Auth::id()
        ]);

        return back()->with('success', 'Reserva marcada como completada.');
    }

    // Marcar como no completada
    public function markNotCompleted(Reservation $reservation)
    {
        $reservation->update([
            'completion_status' => 'no_completada',
            'managed_by' => Auth::id()
        ]);

        return back()->with('success', 'Reserva marcada como no completada.');
    }

    // Ver detalles de una reserva
    public function show(Reservation $reservation)
    {
        $reservation->load('user', 'manager');
        return view('admin.reservations.show', compact('reservation'));
    }
}
