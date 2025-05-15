<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\TendikController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::pattern('id', '[0-9]+');

// Login & Register
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister']);

// Grup rute yang butuh autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('home');

    // Rute kampus
    Route::prefix('kampus')->group(function () {
        Route::get('/', [KampusController::class, 'index']);
        Route::post('/list', [KampusController::class, 'list']);
        Route::get('/{id}/show_ajax', [KampusController::class, 'show_ajax']);
        Route::get('/create_ajax', [KampusController::class, 'create_ajax']);
        Route::post('/ajax', [KampusController::class, 'store_ajax']);
        Route::get('/{id}/delete_ajax', [KampusController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [KampusController::class, 'delete_ajax']);
        Route::get('/{id}/edit_ajax', [KampusController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [KampusController::class, 'update_ajax']);
        Route::get('import', [KampusController::class, 'import']);
        Route::post('import_ajax', [KampusController::class, 'import_ajax']);
        Route::get('export_excel', [KampusController::class, 'export_excel']); 
        Route::get('export_pdf', [KampusController::class, 'export_pdf']);
    });

    // Rute Jurusan
    Route::prefix('jurusan')->group(function () {
        Route::get('/', [JurusanController::class, 'index']);
    });

    // Rute Prodi
    Route::prefix('prodi')->group(function () {
        Route::get('/', [ProdiController::class, 'index']);
    });

    // Rute Admin
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
    });

    // Rute Mahasiswa
    Route::prefix('mahasiswa')->group(function () {
        Route::get('/', [MahasiswaController::class, 'index']);
    });

    // Rute Dosen
    Route::prefix('dosen')->group(function () {
        Route::get('/', [DosenController::class, 'index']);
    });

    // Rute BIODATA
    Route::prefix('biodata')->group(function () {
        Route::get('/tendik', [TendikController::class, 'index'])->name('biodata.tendik.index');
        Route::get('/tendik/data', [TendikController::class, 'getData'])->name('biodata.tendik.data');
    });

    // Rute user
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
    });
});
