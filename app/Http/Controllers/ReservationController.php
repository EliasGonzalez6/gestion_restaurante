<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // Mostrar página de reservas (redirige según autenticación)
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('reservations.create');
        }
        return view('reservations.index');
    }

    // Mostrar formulario de reserva (solo para autenticados)
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('reservations.index');
        }
        return view('reservations.create-reservation');
    }

    // Guardar nueva reserva
    public function store(Request $request)
    {
        $request->validate([
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'number_of_people' => 'required|integer|min:1|max:20',
            'observations' => 'nullable|string|max:500'
        ], [
            'reservation_date.required' => 'La fecha de reserva es obligatoria.',
            'reservation_date.after_or_equal' => 'La fecha debe ser hoy o posterior.',
            'reservation_time.required' => 'La hora de reserva es obligatoria.',
            'number_of_people.required' => 'La cantidad de personas es obligatoria.',
            'number_of_people.min' => 'Debe reservar para al menos 1 persona.',
            'number_of_people.max' => 'El máximo es 20 personas por reserva.',
        ]);

        Reservation::create([
            'user_id' => Auth::id(),
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time . ':00', // Agregar segundos al formato de hora
            'number_of_people' => $request->number_of_people,
            'observations' => $request->observations,
            'status' => 'pendiente',
            'completion_status' => 'pendiente'
        ]);

        return redirect()->route('profile.show')->with('success', '¡Reserva solicitada con éxito! Puedes ver el estado de tu reserva abajo.');
    }

    // Ver las reservas del usuario en su perfil
    public function myReservations()
    {
        $reservations = Auth::user()->reservations()->latest()->get();
        return view('reservations.my-reservations', compact('reservations'));
    }

    // Cancelar una reserva (solo el usuario)
    public function cancel(Reservation $reservation)
    {
        // Verificar que el usuario sea el dueño de la reserva
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        // Verificar si puede cancelar (hasta 1 día antes)
        if (!$reservation->canBeCanceled()) {
            return back()->with('error', 'No puedes cancelar esta reserva. Solo se permite cancelar hasta 1 día antes de la fecha programada.');
        }

        $reservation->update([
            'status' => 'cancelada',
            'canceled_at' => now()
        ]);

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }
}
