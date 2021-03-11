<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PekerjaanController;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PublikasiController;
use App\Http\Controllers\TabelDinamisController;
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
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
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
            Route::post('/revisi/{id}', [PublikasiController::class, 'revisi']);
            Route::post('/', [PublikasiController::class, 'store']);
            Route::put('/sprp/{id}', [PublikasiController::class, 'sprp']);
            Route::put('/{id}', [PublikasiController::class, 'update']);
            Route::post('/import', [PublikasiController::class, 'import']);
            Route::delete('/', [PublikasiController::class, 'destroy']);
            Route::get('/{id}', [PublikasiController::class, 'show']);
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
            Route::get('/search', [PekerjaanController::class, 'search']);
            Route::post('/', [PekerjaanController::class, 'store']);
            Route::put('/kerjakan/{id}', [PekerjaanController::class, 'kerjakan']);
            Route::put('/batal/{id}', [PekerjaanController::class, 'batal']);
            Route::put('/', [PekerjaanController::class, 'update']);
            Route::delete('/', [PekerjaanController::class, 'destroy']);
        });

        Route::prefix('tabelDinamis')->group(function () {
            Route::get('/', [TabelDinamisController::class, 'index']);
            Route::get('/countDinamis', [TabelDinamisController::class, 'countDinamis']);
            Route::get('/{id}', [TabelDinamisController::class, 'show']);
            Route::post('/', [TabelDinamisController::class, 'store']);
            Route::put('/requestDelete', [TabelDinamisController::class, 'requestDestroy']);
            Route::put('/{id}', [TabelDinamisController::class, 'update']);
            Route::delete('/', [TabelDinamisController::class, 'destroy'])->middleware('can:isAdmin');
            Route::get('/all', [TabelDinamisController::class, 'all']);
            Route::post('/sync', [TabelDinamisController::class, 'sync'])->middleware('can:isAdmin');
        });

        Route::prefix('notifikasi')->group(function () {
            Route::get('/', [NotifikasiController::class, 'index']);
            Route::post('/{id}', [NotifikasiController::class, 'update']);
        });
    });

});