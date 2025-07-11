<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\AtelierController;
use App\Http\Controllers\InscriptionController;
use Illuminate\Http\Request;

// Routes publiques
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/ateliers', [AtelierController::class, 'index']);
Route::get('/ateliers/{id}', [AtelierController::class, 'show']);
Route::get('/inscriptions/{id}', [InscriptionController::class, 'show']);

// Routes protégées par authentification sanctum
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Utilisateurs - admin only
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('utilisateurs', UtilisateurController::class);
        Route::delete('/inscriptions/{atelier}', [InscriptionController::class, 'destroyByAtelier']);
    });

    // Ateliers - admin & formateur
    Route::middleware('role:admin,formateur')->group(function () {
        Route::post('/ateliers', [AtelierController::class, 'store']);
        Route::put('/ateliers/{id}', [AtelierController::class, 'update']);
        Route::delete('/ateliers/{id}', [AtelierController::class, 'destroy']);
        Route::get('/ateliers/{id}/participants', [AtelierController::class, 'participants']);
    });

    // Inscriptions routes
    // Participant: inscription POST + liste de ses inscriptions GET
    Route::middleware('role:participant')->group(function () {
        Route::post('/inscriptions', [InscriptionController::class, 'store']);
        Route::get('/mes-inscriptions', [InscriptionController::class, 'mesInscriptions']);
        //Route::delete('/inscriptions/{atelier}', [InscriptionController::class, 'destroyByAtelier']);
        Route::delete('/inscriptions/atelier/{atelierId}', [InscriptionController::class, 'destroyByAtelier']);
    });

    // Admin & formateur: gestion complète inscriptions
    Route::middleware('role:admin,formateur')->group(function () {
        Route::get('/inscriptions', [InscriptionController::class, 'index']);
        Route::delete('/inscriptions/{id}', [InscriptionController::class, 'destroy']);
    });
});
