<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    // Nombre exacto de tu tabla según el archivo SQL
    protected $table = 'servicios';

    // Desactivamos marcas automáticas de actualización ya que no usas updated_at
    public $timestamps = false;

    protected $fillable = ['nombre_servicio', 'precio'];

    // Relación opcional: Un servicio puede estar en muchas ventas registradas
    public function ventas()
    {
        return $this->hasMany(VentaServicio::class, 'servicio_id');
    }
}