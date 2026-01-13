<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\PengembalianController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================
// AUTH (PUBLIC)
// =========================
Route::post('/login', [AuthController::class, 'login']);
// Public test routes (for Postman without auth)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/test/kategori', [KategoriController::class, 'store']);
Route::post('/test/buku', [BukuController::class, 'store']);
Route::post('/test/peminjaman', [PeminjamanController::class, 'pinjam']);
// Debug endpoint to echo received payload (helps diagnose Postman body issues)
Route::post('/debug/kategori', function (Request $request) {
    $raw = file_get_contents('php://input');
    return response()->json([
        'received' => $request->all(),
        'raw' => $raw
    ]);
});
// Make kategori index/create public for quick Postman testing
Route::get('/kategori', [KategoriController::class, 'index']);
Route::post('/kategori', [KategoriController::class, 'store']);

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
        Route::post('/{id}',   [KategoriController::class, 'update']);
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

    // =========================
    // PEMINJAMAN ROUTES
    // =========================
    Route::prefix('peminjaman')->group(function () {
        Route::get('/',     [PeminjamanController::class, 'index']); // list peminjaman
        Route::post('/',    [PeminjamanController::class, 'pinjam']); // pinjam buku
        Route::get('/{id}', [PeminjamanController::class, 'show']);
    });

    // =========================
    // PENGEMBALIAN ROUTES
    // =========================
    Route::prefix('pengembalian')->group(function () {
        Route::post('/', [PengembalianController::class, 'store']); // kembalikan buku
    });

});
