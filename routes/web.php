<?php

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\MunicipioController;
use Illuminate\Support\Facades\Route;

// rota principal exibe form + possíveis resultados
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

// ao submeter, cai aqui e retorna a mesma view com dados/flash
Route::post('/buscar', [WeatherController::class, 'search'])->name('weather.search');

// autocomplete de municípios (GET /municipios?q=...)
Route::get('/municipios', [MunicipioController::class, 'search'])->name('municipios.search');
