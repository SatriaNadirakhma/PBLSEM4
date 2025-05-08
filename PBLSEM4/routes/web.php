<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;

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

Route::get('/', [WelcomeController::class, 'index']);

// Data User Routes
Route::prefix('user')->group(function() {
    Route::get('admin', [UserController::class, 'admin'])->name('user-admin');
    Route::get('mahasiswa', [UserController::class, 'mahasiswa'])->name('user-mahasiswa');
    Route::get('dosen', [UserController::class, 'dosen'])->name('user-dosen');
    Route::get('tendik', [UserController::class, 'tendik'])->name('user-tendik');
});


