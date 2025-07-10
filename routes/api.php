<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\UtilisateurController;
use App\Http\Controllers\AtelierController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\AuthController;

// 🔧 Test de log
Route::get('/test-log', function () {
    Log::info('Test log simple à ' . now());
    return 'Log testé';
});

// 🔓 Routes publiques
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('ateliers-disponibles', [AtelierController::class, 'publicIndex']); // Pour visiteurs non connectés (si besoin)

// 🔒 Routes protégées
Route::middleware('auth:sanctum')->group(function () {

    // 🔐 Déconnexion
    Route::post('logout', [AuthController::class, 'logout']);

    // 🧑‍🏫👑 Accès aux ateliers (admin : tous / formateur : les siens → géré dans le contrôleur)
    Route::apiResource('ateliers', AtelierController::class);
    Route::get('ateliers/{id}/participants', [AtelierController::class, 'participants']);

    // 👑 Routes réservées à l'admin
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('utilisateurs', UtilisateurController::class);
    });

    // 👤 Routes réservées aux participants
    Route::middleware('role:participant')->group(function () {
        Route::apiResource('inscriptions', InscriptionController::class)->only([
            'index', 'show', 'store', 'destroy'
        ]);
    });

    // 🧪 Info utilisateur connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
