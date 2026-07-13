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
Route::post('/ventas/actualizar/{id}', [VentaController::class, 'update'])->name('ventas.update');
Route::post('/ventas/eliminar/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

// 3. Módulo de Barberos
Route::get('/barberos', [EmpleadoController::class, 'index'])->name('barberos.index');
Route::post('/barberos/guardar', [EmpleadoController::class, 'store'])->name('barberos.store');
Route::post('/barberos/actualizar/{id}', [EmpleadoController::class, 'update'])->name('barberos.update');
Route::post('/barberos/eliminar/{id}', [EmpleadoController::class, 'destroy'])->name('barberos.destroy');

// 4. Módulo de Servicios
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::post('/servicios/guardar', [ServicioController::class, 'store'])->name('servicios.store');
Route::post('/servicios/actualizar/{id}', [ServicioController::class, 'update'])->name('servicios.update');
Route::post('/servicios/eliminar/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

// 5. Módulo de Reportes y Finanzas (Estructura Limpia y Completa)
Route::prefix('reportes')->name('reportes.')->group(function () {
    Route::get('/', [ReporteController::class, 'index'])->name('index');
    Route::get('/ventas-detalladas', [ReporteController::class, 'ventasDetalladas'])->name('ventas');
    
    // Vista en navegador del Cierre de Caja
    Route::get('/cierre-caja', [ReporteController::class, 'cierreCaja'])->name('cierre');
    
    // ¡NUEVA RUTA DEFINIDA! Descarga del PDF oficial de Arqueo
    Route::get('/cierre-caja/pdf', [ReporteController::class, 'pdfCierreCaja'])->name('cierre.pdf');
    
    Route::get('/rendimiento-barberos', [ReporteController::class, 'rendimientoBarberos'])->name('barberos');
    Route::get('/top-servicios', [ReporteController::class, 'topServicios'])->name('top');
    Route::get('/descargar-pdf', [ReporteController::class, 'descargarPdf'])->name('pdf');
    Route::get('/ventas/pdf-exclusivo', [ReporteController::class, 'pdfVentasDetalladas'])->name('ventas.pdf');

    // Procesos de Caja Chica (POST)
    Route::post('/caja-chica/guardar', [ReporteController::class, 'guardarCajaChica'])->name('cajachica.guardar');
    Route::post('/caja-chica/eliminar', [ReporteController::class, 'eliminarCajaChica'])->name('cajachica.eliminar');
});

// 6. Módulo de Configuración
Route::get('/configuracion', function () {
    return view('configuracion.index');
})->name('config.index');