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
            Route::post('/', [UserController::class, 'store'])->middleware('can:isAdmin');
            Route::put('/', [UserController::class, 'update'])->middleware('can:isAdmin');
            Route::put('/password', [UserController::class, 'updatePassword'])->middleware('can:isAdmin');
            Route::delete('/', [UserController::class, 'destroy'])->middleware('can:isAdmin');
            Route::get('/all', [UserController::class, 'all'])->middleware('can:isAdmin');
            Route::get('/bidang', [UserController::class, 'bidangAll']);
        });

        Route::prefix('publikasi')->group(function () {
            Route::get('/', [PublikasiController::class, 'index']);
            Route::get('/year', [PublikasiController::class, 'indexYear']);
            Route::get('/countIndexYear', [PublikasiController::class, 'countIndexYear']);
            Route::post('/draft/{id}', [PublikasiController::class, 'draft']);
            Route::post('/', [PublikasiController::class, 'store']);
            Route::put('/sprp/{id}', [PublikasiController::class, 'sprp']);
            Route::put('/{id}', [PublikasiController::class, 'update']);
            Route::get('/search', [PublikasiController::class, 'search']);
            Route::get('/searchYear', [PublikasiController::class, 'searchYear']);
            Route::post('/import', [PublikasiController::class, 'import']);
            Route::delete('/', [PublikasiController::class, 'destroy']);
            Route::get('/{id}', [PublikasiController::class, 'show']);
        });

        Route::prefix('stage')->middleware('can:isAdmin')->group(function () {
            Route::get('/stage_publikasi', [EnumsController::class, 'getStagePublikasi']);
            Route::get('/kode_publikasi', [EnumsController::class, 'getKodePublikasi']);
            Route::get('/kode_tabel', [EnumsController::class, 'destroy']);
        });

        Route::prefix('petugas')->group(function () {
            Route::get('/', [PetugasController::class, 'index'])->middleware('can:isAdmin');
            Route::post('/', [PetugasController::class, 'store'])->middleware('can:isAdmin');
            Route::put('/', [PetugasController::class, 'update'])->middleware('can:isAdmin');
            Route::delete('/', [PetugasController::class, 'destroy'])->middleware('can:isAdmin');
            Route::get('/all', [PetugasController::class, 'all']);
        });

        Route::prefix('pekerjaan')->middleware('can:isAdmin')->group(function () {
            Route::get('/', [PekerjaanController::class, 'index']);
            Route::get('/all', [PekerjaanController::class, 'all']);
            Route::post('/', [PekerjaanController::class, 'store']);
            Route::put('/', [PekerjaanController::class, 'update']);
            Route::delete('/', [PekerjaanController::class, 'destroy']);
        });
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/login', [AuthController::class, 'login']);
});