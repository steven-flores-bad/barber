<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\CajaChicaController;
use App\Http\Controllers\AuthController;

// 1. Redirección de la Raíz al Login
Route::redirect('/', '/login');

// 2. RUTAS DE INVITADOS (Solo accesibles si NO estás logiado)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// 3. RUTAS PROTEGIDAS (Solo accesibles si estás Autenticado)
Route::middleware('auth')->group(function () {
    
    // Panel de Inicio Principal (Usando tu DashboardController)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Módulo de Gestión de Usuarios (Exclusivo para el Administrador)
    Route::get('/configuracion/usuarios', [AuthController::class, 'index'])->name('usuarios.index');
    Route::get('/configuracion/usuarios/nuevo', [AuthController::class, 'showRegister'])->name('usuarios.create');
    Route::post('/configuracion/usuarios/guardar', [AuthController::class, 'register'])->name('usuarios.store');

    // Rutas para Editar y Eliminar Usuarios (Solo Admin)
    Route::get('/configuracion/usuarios/editar/{id}', [AuthController::class, 'edit'])->name('usuarios.edit');
    Route::post('/configuracion/usuarios/actualizar/{id}', [AuthController::class, 'update'])->name('usuarios.update');
    Route::delete('/configuracion/usuarios/eliminar/{id}', [AuthController::class, 'destroy'])->name('usuarios.destroy');

    // Módulo de Ventas
    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::post('/ventas/guardar', [VentaController::class, 'store'])->name('ventas.store');
    Route::post('/ventas/actualizar/{id}', [VentaController::class, 'update'])->name('ventas.update');
    Route::post('/ventas/eliminar/{id}', [VentaController::class, 'destroy'])->name('ventas.destroy');

    // Módulo de Barberos
    Route::get('/barberos', [EmpleadoController::class, 'index'])->name('barberos.index');
    Route::post('/barberos/guardar', [EmpleadoController::class, 'store'])->name('barberos.store');
    Route::post('/barberos/actualizar/{id}', [EmpleadoController::class, 'update'])->name('barberos.update');
    Route::post('/barberos/eliminar/{id}', [EmpleadoController::class, 'destroy'])->name('barberos.destroy');

    // Módulo de Servicios
    Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::post('/servicios/guardar', [ServicioController::class, 'store'])->name('servicios.store');
    Route::post('/servicios/actualizar/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::post('/servicios/eliminar/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

    // Módulo de Reportes y Finanzas
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('/ventas-detalladas', [ReporteController::class, 'ventasDetalladas'])->name('ventas');
        Route::get('/cierre-caja', [ReporteController::class, 'cierreCaja'])->name('cierre');
        Route::get('/cierre-caja/pdf', [ReporteController::class, 'pdfCierreCaja'])->name('cierre.pdf');
        Route::get('/rendimiento-barberos', [ReporteController::class, 'rendimientoBarberos'])->name('barberos');
        Route::get('/top-servicios', [ReporteController::class, 'topServicios'])->name('top');
        Route::get('/descargar-pdf', [ReporteController::class, 'descargarPdf'])->name('pdf');
        Route::get('/ventas/pdf-exclusivo', [ReporteController::class, 'pdfVentasDetalladas'])->name('ventas.pdf');
        Route::post('/caja-chica/guardar', [ReporteController::class, 'guardarCajaChica'])->name('cajachica.guardar');
        Route::post('/caja-chica/eliminar', [ReporteController::class, 'eliminarCajaChica'])->name('cajachica.eliminar');
    });

    // Módulo de Historial de Cajas Chicas
    Route::prefix('caja-chica-historial')->name('cajachica.')->group(function () {
        Route::get('/', [CajaChicaController::class, 'index'])->name('index');
        Route::post('/actualizar/{id}', [CajaChicaController::class, 'update'])->name('update');
        Route::post('/eliminar/{id}', [CajaChicaController::class, 'destroy'])->name('destroy');
        Route::get('/descargar-pdf', [CajaChicaController::class, 'descargarPdf'])->name('pdf');
    });

    // Módulo de Configuración
    Route::get('/configuracion', function () {
        return view('configuracion.index');
    })->name('config.index');

    // Botón de salida
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});