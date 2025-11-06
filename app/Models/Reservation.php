<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reservation_date',
        'reservation_time',
        'number_of_people',
        'observations',
        'status',
        'completion_status',
        'managed_by',
        'canceled_at'
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'canceled_at' => 'datetime',
    ];

    // Relación con el usuario que hace la reserva
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con el administrador que gestiona la reserva
    public function manager()
    {
        return $this->belongsTo(User::class, 'managed_by');
    }

    // Verificar si la reserva puede ser cancelada (hasta 1 día antes)
    public function canBeCanceled()
    {
        // No se puede cancelar si ya fue completada o no completada
        if ($this->completion_status === 'completada' || $this->completion_status === 'no_completada') {
            return false;
        }

        // No se puede cancelar si está rechazada o ya cancelada
        if ($this->status !== 'pendiente' && $this->status !== 'aceptada') {
            return false;
        }

        // Obtener solo la fecha (YYYY-MM-DD) y concatenar con la hora
        $dateOnly = Carbon::parse($this->reservation_date)->format('Y-m-d');
        $reservationDateTime = Carbon::parse($dateOnly . ' ' . $this->reservation_time);
        $oneDayBefore = $reservationDateTime->copy()->subDay();
        
        return now()->lessThan($oneDayBefore);
    }

    // Scope para reservas pendientes
    public function scopePending($query)
    {
        return $query->where('status', 'pendiente');
    }

    // Scope para reservas aceptadas
    public function scopeAccepted($query)
    {
        return $query->where('status', 'aceptada');
    }

    // Scope para reservas rechazadas
    public function scopeRejected($query)
    {
        return $query->where('status', 'rechazada');
    }

    // Scope para reservas canceladas
    public function scopeCanceled($query)
    {
        return $query->where('status', 'cancelada');
    }

    // Scope para reservas completadas
    public function scopeCompleted($query)
    {
        return $query->where('completion_status', 'completada');
    }

    // Obtener el nombre del estado en español
    public function getStatusNameAttribute()
    {
        // Prioridad: completion_status sobre status
        if ($this->completion_status === 'completada') {
            return 'Completada';
        }
        
        if ($this->completion_status === 'no_completada') {
            return 'No Completada';
        }

        $statuses = [
            'pendiente' => 'Pendiente',
            'aceptada' => 'Aceptada',
            'rechazada' => 'Rechazada',
            'cancelada' => 'Cancelada'
        ];

        return $statuses[$this->status] ?? 'Desconocido';
    }

    // Obtener el color del badge según el estado
    public function getStatusColorAttribute()
    {
        // Prioridad: completion_status sobre status
        if ($this->completion_status === 'completada') {
            return 'success';
        }
        
        if ($this->completion_status === 'no_completada') {
            return 'danger';
        }

        $colors = [
            'pendiente' => 'warning',
            'aceptada' => 'success',
            'rechazada' => 'danger',
            'cancelada' => 'secondary'
        ];

        return $colors[$this->status] ?? 'dark';
    }
}
