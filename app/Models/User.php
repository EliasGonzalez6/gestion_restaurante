<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Valor por defecto para rol
      protected $attributes = [
        'roles_id' => 1,
    ];

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'phone',
        'address',
        'photo',
        'roles_id',
    ];

    // Campos ocultos (no se muestran en arrays o JSON)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast de datos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación con rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'roles_id');
    }
}
