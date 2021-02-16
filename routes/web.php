<?php

use App\Http\Controllers\FuncionesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Bienvenido jefe de comunicaciones!';
});

Route::post('/topsecret_split/{id}', [FuncionesController::class, 'topsecret_split']);
Route::post('/topsecret', [FuncionesController::class, 'topsecret']);
