<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KampusController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DetailPendaftaranController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\HasilUjianController;
use App\Http\Controllers\InformasiController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\TendikController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\LandingPageController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::pattern('id', '[0-9]+');

Route::get('/', [LandingPageController::class, 'index'])->name('templatepage');

// Login & Register
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'postRegister']);

// Grup rute yang butuh autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

     Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update-photo', [UserController::class, 'updatePhoto'])->name('profile.updatePhoto');

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
        Route::post('/list', [JurusanController::class, 'list']);
        Route::get('/{id}/show_ajax', [JurusanController::class, 'show_ajax']);
        Route::get('/create_ajax', [JurusanController::class, 'create_ajax']);
        Route::post('/ajax', [JurusanController::class, 'store_ajax']);
        Route::get('/{id}/edit_ajax', [JurusanController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [JurusanController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [JurusanController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [JurusanController::class, 'delete_ajax']);
        Route::get('import', [JurusanController::class, 'import']);
        Route::post('import_ajax', [JurusanController::class, 'import_ajax']);
        Route::get('export_excel', [JurusanController::class, 'export_excel']); 
        Route::get('export_pdf', [JurusanController::class, 'export_pdf']);
    });

    // Rute Prodi
    Route::prefix('prodi')->group(function () {
        Route::get('/', [ProdiController::class, 'index']);
        Route::post('/list', [ProdiController::class, 'list']);
        Route::get('/{id}/show_ajax', [ProdiController::class, 'show_ajax']);
        Route::get('/create_ajax', [ProdiController::class, 'create_ajax']);
        Route::post('/ajax', [ProdiController::class, 'store_ajax']);
        Route::get('/{id}/delete_ajax', [ProdiController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [ProdiController::class, 'delete_ajax']);
        Route::get('/{id}/edit_ajax', [ProdiController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [ProdiController::class, 'update_ajax']);
        Route::get('import', [ProdiController::class, 'import']);
        Route::post('import_ajax', [ProdiController::class, 'import_ajax']);
        Route::get('export_excel', [ProdiController::class, 'export_excel']); 
        Route::get('export_pdf', [ProdiController::class, 'export_pdf']);
    });

    // Rute Admin
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::post('/list', [AdminController::class, 'list']);
        Route::get('/{id}/show_ajax', [AdminController::class, 'show_ajax']);
        Route::get('/create_ajax', [AdminController::class, 'create_ajax']);
        Route::post('/ajax', [AdminController::class, 'store_ajax']);
        Route::get('/{id}/delete_ajax', [AdminController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [AdminController::class, 'delete_ajax']);
        Route::get('/{id}/edit_ajax', [AdminController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [AdminController::class, 'update_ajax']);
        Route::get('import', [AdminController::class, 'import']);
        Route::post('import_ajax', [AdminController::class, 'import_ajax']);
        Route::get('export_excel', [AdminController::class, 'export_excel']); 
        Route::get('export_pdf', [AdminController::class, 'export_pdf']);
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
    Route::prefix('biodata/tendik')->name('biodata.tendik.')->group(function () {
        Route::get('/', [TendikController::class, 'index'])->name('index');
        Route::post('/list', [TendikController::class, 'list'])->name('list');
        Route::get('/{id}/show_ajax', [TendikController::class, 'show_ajax'])->name('show_ajax');
        Route::get('/create_ajax', [TendikController::class, 'create_ajax'])->name('create_ajax');
        Route::post('/store_ajax', [TendikController::class, 'store_ajax'])->name('store_ajax');
        Route::get('/{id}/delete_ajax', [TendikController::class, 'confirm_ajax'])->name('confirm_ajax');
        Route::delete('/{id}/delete_ajax', [TendikController::class, 'delete_ajax'])->name('delete_ajax');
        Route::get('/{id}/edit_ajax', [TendikController::class, 'edit_ajax'])->name('edit_ajax');
        Route::put('/{id}/update_ajax', [TendikController::class, 'update_ajax'])->name('update_ajax');
        Route::get('/import', [TendikController::class, 'import'])->name('import');
        Route::post('/import_ajax', [TendikController::class, 'import_ajax'])->name('import_ajax');
        Route::get('/export_excel', [TendikController::class, 'export_excel'])->name('export_excel');
        Route::get('/export_pdf', [TendikController::class, 'export_pdf'])->name('export_pdf');
    });

     Route::prefix('biodata/mahasiswa')->name('biodata.mahasiswa.')->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('index');
        Route::post('/list', [MahasiswaController::class, 'list'])->name('list');
        Route::get('/{id}/show_ajax', [MahasiswaController::class, 'show_ajax'])->name('show_ajax');
        Route::get('/create_ajax', [MahasiswaController::class, 'create_ajax'])->name('create_ajax');
        Route::post('/store_ajax', [MahasiswaController::class, 'store_ajax'])->name('store_ajax');
        Route::get('/{id}/delete_ajax', [MahasiswaController::class, 'confirm_ajax'])->name('confirm_ajax');
        Route::delete('/{id}/delete_ajax', [MahasiswaController::class, 'delete_ajax'])->name('delete_ajax');
        Route::get('/{id}/edit_ajax', [MahasiswaController::class, 'edit_ajax'])->name('edit_ajax');
        Route::put('/{id}/update_ajax', [MahasiswaController::class, 'update_ajax'])->name('update_ajax');
        Route::get('/import', [MahasiswaController::class, 'import'])->name('import');
        Route::post('/import_ajax', [MahasiswaController::class, 'import_ajax'])->name('import_ajax');
        Route::get('/export_excel', [MahasiswaController::class, 'export_excel'])->name('export_excel');
        Route::get('/export_pdf', [MahasiswaController::class, 'export_pdf'])->name('export_pdf');
    });

      Route::prefix('biodata/dosen')->name('biodata.dosen.')->group(function () {
    Route::get('/', [DosenController::class, 'index'])->name('index');
        Route::post('/list', [DosenController::class, 'list'])->name('list');
        Route::get('/{id}/show_ajax', [DosenController::class, 'show_ajax'])->name('show_ajax');
        Route::get('/create_ajax', [DosenController::class, 'create_ajax'])->name('create_ajax');
        Route::post('/store_ajax', [DosenController::class, 'store_ajax'])->name('store_ajax');
        Route::get('/{id}/delete_ajax', [DosenController::class, 'confirm_ajax'])->name('confirm_ajax');
        Route::delete('/{id}/delete_ajax', [DosenController::class, 'delete_ajax'])->name('delete_ajax');
        Route::get('/{id}/edit_ajax', [DosenController::class, 'edit_ajax'])->name('edit_ajax');
        Route::put('/{id}/update_ajax', [DosenController::class, 'update_ajax'])->name('update_ajax');
        Route::get('/import', [DosenController::class, 'import'])->name('import');
        Route::post('/import_ajax', [DosenController::class, 'import_ajax'])->name('import_ajax');
        Route::get('/export_excel', [DosenController::class, 'export_excel'])->name('export_excel');
        Route::get('/export_pdf', [DosenController::class, 'export_pdf'])->name('export_pdf');
    });



    // Rute user
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::post('/list', [UserController::class, 'list'])->name('user.list');
        Route::get('get-nama-by-role/{role}', [UserController::class, 'getNamaByRole']);
        Route::get('get-detail-by-role/{role}/{id}', [UserController::class, 'getDetailByRole']);
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);
        Route::post('/ajax', [UserController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
        // Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
        // Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);

        
    });

    // Rute jadwal
    Route::prefix('jadwal')->group(function () {
        Route::get('/', [JadwalController::class, 'index']);
    }); 

    // Rute pendaftaran     
    Route::prefix('pendaftaran')->group(function () {
        Route::get('/', [PendaftaranController::class, 'index']);
    });

    // Rute detail pendaftaran  
    Route::prefix('detail_pendaftaran')->group(function () {
        Route::get('/', [DetailPendaftaranController::class, 'index']);
    });

    // Rute Ujian
    Route::prefix('ujian')->group(function () {
        Route::get('/', [UjianController::class, 'index']);
    });

    // Rute Hasil Ujian
    Route::prefix('hasil_ujian')->group(function () {
        Route::get('/', [HasilUjianController::class, 'index']);
    });

    // Rute Informasi
    Route::prefix('informasi')->group(function () {
        Route::get('/', [InformasiController::class, 'index']);
        
    });

});
