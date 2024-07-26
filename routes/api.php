<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EspecialidadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registro', [AuthController::class, 'register']);
Route::post('/inicio-sesion', [AuthController::class, 'login']);
Route::post('/cerrar-sesion', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/especialidades', [EspecialidadController::class, 'index']);
    Route::get('/doctores/{especialidad}', [EspecialidadController::class, 'show']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::get('/citas', [CitaController::class, 'index']);
    Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
});
