<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// API routes without prefix for compatibility
Route::middleware('api')->group(function () {
    // AUTH (PUBLIC)
    Route::post('/login', [AuthController::class, 'login']);

    // AUTHENTICATED ROUTES
    Route::middleware('auth:api')->group(function () {
        // USER
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // KATEGORI
        Route::prefix('kategori')->group(function () {
            Route::get('/',        [KategoriController::class, 'index']);
            Route::post('/',       [KategoriController::class, 'store']);
            Route::get('/{id}',    [KategoriController::class, 'show']);
            Route::put('/{id}',    [KategoriController::class, 'update']);
            Route::delete('/{id}', [KategoriController::class, 'destroy']);
        });

        // BUKU
        Route::prefix('buku')->group(function () {
            Route::get('/',        [BukuController::class, 'index']);
            Route::post('/',       [BukuController::class, 'store']);
            Route::get('/{id}',    [BukuController::class, 'show']);
            Route::put('/{id}',    [BukuController::class, 'update']);
            Route::delete('/{id}', [BukuController::class, 'destroy']);
        });
    });
});
