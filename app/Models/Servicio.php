<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Nombre exacto de tu tabla en la base de datos
    protected $table = 'servicios';

    protected $fillable = ['nombre_servicio', 'precio'];
}