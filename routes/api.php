<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\UserController;
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
        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::get('/all', [UserController::class, 'all']);
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

        Route::prefix('petugas')->group(function () {
            Route::get('/', [PetugasController::class, 'index']);
            Route::get('/all', [PetugasController::class, 'all']);
            Route::post('/', [PetugasController::class, 'store']);
            Route::put('/', [PetugasController::class, 'edit']);
            Route::delete('/', [PetugasController::class, 'destroy']);
        });

        Route::prefix('pekerjaan')->group(function () {
            Route::get('/', [PekerjaanController::class, 'index']);
            Route::get('/all', [PekerjaanController::class, 'all']);
            Route::post('/', [PekerjaanController::class, 'store']);
            Route::put('/', [PekerjaanController::class, 'edit']);
            Route::delete('/', [PekerjaanController::class, 'destroy']);
        });
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/login', [AuthController::class, 'login']);
});
