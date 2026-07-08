<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;

// 1. Panel de Inicio
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// 2. Módulo de Ventas
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::post('/ventas/guardar', [VentaController::class, 'store'])->name('ventas.store');

// Recibe el ID de la venta y los datos modificados para guardarlos
Route::post('/ventas/actualizar/{id}', [VentaController::class, 'update'])->name('ventas.update');
// Recibe el ID de la venta para borrarla
Route::post('/ventas/eliminar/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

// 3. Módulo de Barberos
Route::get('/barberos', function () {
    return view('barberos.index');
})->name('barberos.index');

// 4. Módulo de Reportes y Finanzas
Route::get('/reportes', function () {
    return view('reportes.index');
})->name('reportes.index');

// 5. Módulo de Configuración
Route::get('/configuracion', function () {
    return view('configuracion.index');
})->name('config.index');