<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VentaServicio extends Model
{
    // 1 Indicamos el nombre exacto de tu tabla
    protected $table = 'ventas_servicios';
    
    // 2. AGREGA ESTA LÍNEA AQUÍ (Esto le dice a Laravel que no busque creados_at/updated_at automáticamente)
    public $timestamps = false;

    // Relación: Una venta pertenece a un empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }


    // Relación: Una venta pertenece a un servicio/corte
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}