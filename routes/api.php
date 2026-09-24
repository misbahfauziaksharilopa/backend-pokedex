<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EffectivenessController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\TypeController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('forms', FormController::class);
    Route::apiResource('pokemons', PokemonController::class);
});

Route::get('/forms', [FormController::class, 'index']);
Route::get('/forms/{form}', [FormController::class, 'show']);
Route::apiResource('types', TypeController::class)->only(['index', 'show']);
Route::get('/pokemon', [PokemonController::class, 'index']);
Route::get('/pokemon/{pokemon}', [PokemonController::class, 'show']);
Route::apiResource('effectivenesses', EffectivenessController::class)->only(['index', 'show']);