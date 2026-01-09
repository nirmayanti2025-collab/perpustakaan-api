<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================
// AUTH (PUBLIC)
// =========================
Route::post('/login', [AuthController::class, 'login']);

// =========================
// AUTHENTICATED ROUTES
// =========================
Route::middleware('auth:api')->group(function () {

    // ===== USER =====
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // =========================
    // KATEGORI ROUTES
    // =========================
    Route::prefix('kategori')->group(function () {
        Route::get('/',        [KategoriController::class, 'index']);
        Route::post('/',       [KategoriController::class, 'store']);
        Route::get('/{id}',    [KategoriController::class, 'show']);
        Route::put('/{id}',    [KategoriController::class, 'update']);
        Route::delete('/{id}', [KategoriController::class, 'destroy']);
    });

    // =========================
    // BUKU ROUTES
    // =========================
    Route::prefix('buku')->group(function () {
        Route::get('/',        [BukuController::class, 'index']);
        Route::post('/',       [BukuController::class, 'store']);
        Route::get('/{id}',    [BukuController::class, 'show']);
        Route::put('/{id}',    [BukuController::class, 'update']);
        Route::delete('/{id}', [BukuController::class, 'destroy']);
    });
});
