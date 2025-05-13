<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\EspacioController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReporteController;

Route::redirect('/', '/dashboard');

// LOGIN y LOGOUT
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTAS PROTEGIDAS
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clientes', ClienteController::class);
    Route::resource('vehiculos', VehiculoController::class);
    Route::resource('entradas', EntradaController::class);
    Route::resource('salidas', SalidaController::class);
    Route::resource('pagos', PagoController::class);

    Route::prefix('espacios')->group(function () {
        Route::get('/', [EspacioController::class, 'index'])->name('espacios.index');
        Route::get('/create', [EspacioController::class, 'create'])->name('espacios.create');
        Route::post('/', [EspacioController::class, 'store'])->name('espacios.store');
        Route::get('/{espacio}/edit', [EspacioController::class, 'edit'])->name('espacios.edit');
        Route::put('/{espacio}', [EspacioController::class, 'update'])->name('espacios.update');
        Route::delete('/{espacio}', [EspacioController::class, 'destroy'])->name('espacios.destroy');
        Route::get('/asignar/{id}', [EspacioController::class, 'asignar'])->name('espacios.asignar');
        Route::put('/{espacio}/asignar', [EspacioController::class, 'updateAsignacion'])->name('espacios.updateAsignacion');
        Route::post('/{espacio}/liberar', [EspacioController::class, 'liberar'])->name('espacios.liberar');
    });

    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::post('/reportes/generar', [ReporteController::class, 'generar'])->name('reportes.generar');
});
