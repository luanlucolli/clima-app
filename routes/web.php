<?php

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\MunicipioController;
use Illuminate\Support\Facades\Route;

// Rotas existentes de clima
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');
Route::post('/buscar', [WeatherController::class, 'search'])->name('weather.search');

// Nova rota de autocomplete de municípios (apenas GET)
/*
 * Exemplo de uso: /municipios?q=belo
 * Retorna JSON com até 10 itens:
 * [
 *   { "ibge_id": 3106200, "nome": "Belo Horizonte", "uf_sigla": "MG", "uf_nome": "Minas Gerais" },
 *   …
 * ]
 */
Route::get('/municipios', [MunicipioController::class, 'search'])->name('municipios.search');
