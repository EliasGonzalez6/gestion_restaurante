<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'name',
        'description',
    ];

     protected $table = 'roles';

    // Relación con usuarios
    public function users()
    {
        return $this->hasMany(User::class, 'roles_id');
    }
}
