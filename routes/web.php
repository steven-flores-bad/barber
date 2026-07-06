<?php

use Illuminate\Support\Facades\Route;

// 1. Panel de Inicio
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// 2. Módulo de Ventas
Route::get('/ventas/nueva', function () {
    return view('ventas.create');
})->name('ventas.create');

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