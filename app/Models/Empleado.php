<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    // Nombre exacto de tu tabla en la base de datos
    protected $table = 'empleados';

    // Campos que se pueden llenar (opcional, pero buena práctica)
    protected $fillable = ['nombre', 'telefono', 'estado'];
}