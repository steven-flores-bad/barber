<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ReporteController;

// 1. Panel de Inicio

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 2. Módulo de Ventas
Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
Route::post('/ventas/guardar', [VentaController::class, 'store'])->name('ventas.store');

// Recibe el ID de la venta y los datos modificados para guardarlos
Route::post('/ventas/actualizar/{id}', [VentaController::class, 'update'])->name('ventas.update');
// Recibe el ID de la venta para borrarla
Route::post('/ventas/eliminar/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

// 3. Módulo de Barberos
Route::get('/barberos', [EmpleadoController::class, 'index'])->name('barberos.index');
Route::post('/barberos/guardar', [EmpleadoController::class, 'store'])->name('barberos.store');
Route::post('/barberos/actualizar/{id}', [EmpleadoController::class, 'update'])->name('barberos.update');
Route::post('/barberos/eliminar/{id}', [EmpleadoController::class, 'destroy'])->name('barberos.destroy');


// 4. Modulo de Servicios
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::post('/servicios/guardar', [ServicioController::class, 'store'])->name('servicios.store');
Route::post('/servicios/actualizar/{id}', [ServicioController::class, 'update'])->name('servicios.update');
Route::post('/servicios/eliminar/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

// 5. Módulo de Reportes y Finanzas
Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::get('/reportes/pdf', [ReporteController::class, 'descargarPdf'])->name('reportes.pdf');



// 6. Módulo de Configuración
Route::get('/configuracion', function () {
    return view('configuracion.index');
})->name('config.index');