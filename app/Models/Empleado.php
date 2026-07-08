<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    // Nombre exacto de tu tabla
    protected $table = 'empleados';

    // Desactivamos el control automático de timestamps de Laravel
    public $timestamps = false;

    protected $fillable = ['nombre', 'telefono', 'estado'];

    // Opcional: Relación inversa (Un barbero tiene muchas ventas)
    public function ventas()
    {
        return $this->hasMany(VentaServicio::class, 'empleado_id');
    }
}