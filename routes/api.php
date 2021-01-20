<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::prefix('publikasi')->group(function () {
            Route::get('/', [PublikasiController::class, 'index']);
            Route::get('/search', [PublikasiController::class, 'search']);
            Route::post('/import', [PublikasiController::class, 'import']);
            Route::delete('/', [PublikasiController::class, 'destroy']);
        });

        Route::prefix('stage')->group(function () {
            Route::get('/stage_publikasi', [EnumsController::class, 'getStagePublikasi']);
            Route::get('/kode_publikasi', [EnumsController::class, 'getKodePublikasi']);
            Route::get('/kode_tabel', [EnumsController::class, 'destroy']);
        });
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/login', [AuthController::class, 'login']);
});