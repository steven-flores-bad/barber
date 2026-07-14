<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CajaChica extends Model
{
    use HasFactory;

    protected $table = 'cajas_chicas';

    protected $fillable = [
        'fecha',
        'monto_inicial'
    ];
}