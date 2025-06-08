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
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\VerifikasiPendaftaranController;
use App\Http\Controllers\DataDiriController;
use App\Http\Controllers\KirimPesanController;
use App\Http\Controllers\HasilPesertaController;
use App\Http\Controllers\RiwayatPesertaController;
use App\Http\Controllers\KirimEmailController;
use App\Http\Controllers\PanduanController;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\PasswordResetController;

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::pattern('id', '[0-9]+');

// Route ke landing utama
Route::get('/', [LandingPageController::class, 'index'])->name('landingpage');

// Route ke halaman about
Route::get('/about', function () {
    return view('about.bodysection');
})->name('aboutpage');


// Login & Register
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// Grup rute yang butuh autentikasi
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');
    // --- Tambahkan Route ini untuk Server-Sent Events (SSE) ---
    // Tidak ada middleware otorisasi tambahan di sini.
    // Otorisasi akan ditangani di dalam method chartDataStream().
    Route::get('/dashboard/chart-stream', [DashboardController::class, 'chartDataStream'])
        ->name('dashboard.chart.stream');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/update-photo', [UserController::class, 'updatePhoto'])->name('profile.updatePhoto');

    // Rute kampus
    Route::prefix('kampus')->middleware(['auth', 'role:admin'])->group(function () {
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
    Route::prefix('jurusan')->middleware(['auth', 'role:admin'])->group(function () {
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
    Route::prefix('prodi')->middleware(['auth', 'role:admin'])->group(function () {
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
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
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
    Route::prefix('biodata/tendik')->name('biodata.tendik.')->middleware(['auth', 'role:admin'])->group(function () {
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

Route::prefix('biodata/mahasiswa')->name('biodata.mahasiswa.')->middleware(['auth', 'role:admin,mahasiswa'])->group(function () {
    Route::get('/', [MahasiswaController::class, 'index'])->name('index');
    Route::get('/list', [MahasiswaController::class, 'list'])->name('list');
    Route::get('{id}/show_ajax', [MahasiswaController::class, 'show_ajax'])->name('show_ajax');
    Route::get('/create_ajax', [MahasiswaController::class, 'create_ajax'])->name('create_ajax');
    Route::post('/store_ajax', [MahasiswaController::class, 'store_ajax'])->name('store_ajax');
    Route::get('/{id}/delete_ajax', [MahasiswaController::class, 'confirm_ajax'])->name('confirm_ajax');
    Route::delete('/{id}/delete_ajax', [MahasiswaController::class, 'delete_ajax'])->name('delete_ajax');
    Route::get('/{id}/edit_ajax', [MahasiswaController::class, 'edit_ajax'])->name('edit_ajax');
    Route::put('/{id}/update_ajax', [MahasiswaController::class, 'update_ajax'])->name('update_ajax');
    Route::get('/import', [MahasiswaController::class, 'import'])->name('import');
    Route::post('/import_ajax', [MahasiswaController::class, 'import_ajax'])->name('mahasiswa.import_ajax');
    Route::get('/export_excel', [MahasiswaController::class, 'export_excel'])->name('export_excel');
    Route::get('/export_pdf', [MahasiswaController::class, 'export_pdf'])->name('export_pdf');
});

  Route::prefix('biodata/dosen')->middleware(['auth', 'role:admin'])->name('biodata.dosen.')->group(function () {
    Route::get('/', [DosenController::class, 'index'])->name('index');
    Route::get('/list', [DosenController::class, 'list'])->name('list');
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
    Route::prefix('user')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::post('/list', [UserController::class, 'list'])->name('user.list');
        Route::get('get-nama-by-role/{role}', [UserController::class, 'getNamaByRole']);
        Route::get('get-detail-by-role/{role}/{id}', [UserController::class, 'getDetailByRole']);
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);
        Route::post('/ajax', [UserController::class, 'store_ajax']);
        Route::get('/{id}/show_ajax', [UserController::class, 'show_ajax']);
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']);
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']);
        Route::get('import', [UserController::class, 'import']);
        Route::post('import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/export_excel', [UserController::class, 'export_excel']);
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);
    });

    // ROUTE VERIFIKASI
    Route::prefix('verifikasi')->middleware(['auth', 'role:admin'])->group(function () {
        Route::get('/', [VerifikasiPendaftaranController::class, 'index'])->name('index');
        Route::get('/list', [VerifikasiPendaftaranController::class, 'list'])->name('verifikasi.list'); // pastikan ini GET
        Route::post('{id}/update', [VerifikasiPendaftaranController::class, 'update'])->name('update');
        Route::get('{id}/edit', [VerifikasiPendaftaranController::class, 'edit'])->name('edit');
        Route::get('/show/{id}', [VerifikasiPendaftaranController::class, 'show'])->name('verifikasi.show');
    });




    // Rute jadwal
    Route::prefix('jadwal')->group(function () {
        Route::get('/', [JadwalController::class, 'index']);
        Route::post('/list', [JadwalController::class, 'list'])->name('jadwal.list');
        Route::get('/{id}/show_ajax', [JadwalController::class, 'show_ajax'])->name('jadwal.show_ajax');
        Route::get('/create_ajax', [JadwalController::class, 'create_ajax'])->name('jadwal.create_ajax');
        Route::post('/ajax', [JadwalController::class, 'store_ajax'])->name('jadwal.store_ajax');
        Route::get('/{id}/delete_ajax', [JadwalController::class, 'confirm_ajax'])->name('jadwal.confirm_ajax');
        Route::delete('/{id}/delete_ajax', [JadwalController::class, 'delete_ajax'])->name('jadwal.delete_ajax');
        Route::get('/{id}/edit_ajax', [JadwalController::class, 'edit_ajax'])->name('jadwal.edit_ajax');
        Route::put('/{id}/update_ajax', [JadwalController::class, 'update_ajax'])->name('jadwal.update_ajax');
        Route::get('/import', [JadwalController::class, 'import'])->name('jadwal.import');
        Route::post('/import_ajax', [JadwalController::class, 'import_ajax'])->name('jadwal.import_ajax');
        Route::get('/export_excel', [JadwalController::class, 'export_excel'])->name('jadwal.export_excel');
        Route::get('/export_pdf', [JadwalController::class, 'export_pdf'])->name('jadwal.export_pdf');
        Route::get('/jadwal/download-template', [JadwalController::class, 'download_template']);


    });
    
    // Route untuk semua role melihat panduan (di dalam grup middleware 'auth')
    Route::get('/panduan', [PanduanController::class, 'show'])->name('panduan.show');

   // Route untuk admin mengelola panduan
    Route::prefix('kelola')->middleware(['role:admin'])->group(function () {
        Route::get('/panduan', [PanduanController::class, 'adminIndex'])->name('panduan.admin.index'); // <-- Ini sudah benar
        Route::post('/panduan/upload', [PanduanController::class, 'upload'])->name('panduan.admin.upload');
    });

    // Rute pendaftaran     
    Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
        Route::get('/', [PendaftaranController::class, 'index'])->name('index');
        Route::post('/store_ajax', [PendaftaranController::class, 'store_ajax'])->name('store_ajax');
    });

    //Rute Riwayat Pendaftaran
    Route::prefix('riwayat')->group(function () {
        Route::get('/', [RiwayatController::class, 'index'])->name('riwayat.index');
        Route::post('/list', [RiwayatController::class, 'list'])->name('riwayat.list');
        Route::get('/{id}/show_ajax', [RiwayatController::class, 'show_ajax']);
        Route::get('export_pdf', [RiwayatController::class, 'export_pdf'])->name('riwayat.export_pdf');
    });

    //Rute Hasil Peserta
    Route::prefix('hasilPeserta')->group(function () {
        Route::get('/', [HasilPesertaController::class, 'index'])->name('hasilPeserta.index');

    });

    //Rute Riwayat Peserta
    Route::prefix('riwayatPeserta')->group(function () {
        Route::get('/', [RiwayatPesertaController::class, 'index'])->name('riwayatPeserta.index');
        Route::get('/{id}', [RiwayatPesertaController::class, 'show'])->name('riwayatPeserta.show');

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
        Route::post('/list', [HasilUjianController::class, 'list'])->name('hasil_ujian.list');
        Route::get('/{id}/show_ajax', [HasilUjianController::class, 'show_ajax'])->name('hasil_ujian.show_ajax');
        Route::get('/create_ajax', [HasilUjianController::class, 'create_ajax'])->name('hasil_ujian.create_ajax');
        Route::post('/ajax', [HasilUjianController::class, 'store_ajax'])->name('hasil_ujian.store_ajax');
        Route::get('/{id}/delete_ajax', [HasilUjianController::class, 'confirm_ajax'])->name('hasil_ujian.confirm_ajax'); 
        Route::delete('/{id}/delete_ajax', [HasilUjianController::class, 'delete_ajax'])->name('hasil_ujian.delete_ajax');
        Route::get('/{id}/edit_ajax', [HasilUjianController::class, 'edit_ajax'])->name('hasil_ujian.edit_ajax');
        Route::put('/{id}/update_ajax', [HasilUjianController::class, 'update_ajax'])->name('hasil_ujian.update_ajax');
        Route::get('/import', [HasilUjianController::class, 'import'])->name('hasil_ujian.import');
        Route::post('/import_ajax', [HasilUjianController::class, 'import_ajax'])->name('hasil_ujian.import_ajax');
        Route::get('/export_excel', [HasilUjianController::class, 'export_excel'])->name('hasil_ujian.export_excel');
        Route::get('/export_pdf', [HasilUjianController::class, 'export_pdf'])->name('hasil_ujian.export_pdf');
        Route::get('/get-users-by-role', [HasilUjianController::class, 'getUsersByRole'])->name('get.users.by.role');
        Route::get('/download_template', [HasilUjianController::class, 'download_template'])->name('hasil_ujian.download');
        Route::get('/hasil_ujian/{id}/delete_ajax', [HasilUjianController::class, 'deleteModal']);
        Route::delete('/hasil_ujian/{id}/delete_ajax', [HasilUjianController::class, 'delete_ajax']);
        Route::post('/hasil_ujian/list', [HasilUjianController::class, 'list']);
        Route::get('/hasil_ujian/{id}/show_ajax', [HasilUjianController::class, 'show_ajax']);
        Route::get('/hasil_ujian/{id}/edit_ajax', [HasilUjianController::class, 'edit_ajax']);
        Route::get('/get-users-by-role', [UserController::class, 'getUsersByRole']);


    });
    
    // Rute Informasi
    Route::prefix('informasi')->group(function () {
        Route::get('/', [InformasiController::class, 'index']);
        Route::post('/list', [InformasiController::class, 'list'])->name('informasi.list');
        Route::get('/{id}/show_ajax', [InformasiController::class, 'show_ajax'])->name('informasi.show_ajax');
        Route::get('/create_ajax', [InformasiController::class, 'create_ajax'])->name('informasi.create_ajax');
        Route::post('/ajax', [InformasiController::class, 'store_ajax'])->name('informasi.store_ajax');
        Route::get('/{id}/delete_ajax', [InformasiController::class, 'confirm_ajax'])->name('informasi.confirm_ajax');  
        Route::delete('/{id}/delete_ajax', [InformasiController::class, 'delete_ajax'])->name('informasi.delete_ajax');
        Route::get('/{id}/edit_ajax', [InformasiController::class, 'edit_ajax'])->name('informasi.edit_ajax');
        Route::put('/{id}/update_ajax', [InformasiController::class, 'update_ajax'])->name('informasi.update_ajax');
        Route::get('/import', [InformasiController::class, 'import'])->name('informasi.import');
        Route::post('/import_ajax', [InformasiController::class, 'import_ajax'])->name('informasi.import_ajax');
        Route::get('/export_excel', [InformasiController::class, 'export_excel'])->name('informasi.export_excel');
        Route::get('/export_pdf', [InformasiController::class, 'export_pdf'])->name('informasi.export_pdf');
        Route::get('/informasi/download-template', [InformasiController::class, 'download_template']);
        
        
    });

    // RUTE DATA DIRI
    Route::prefix('datadiri')->middleware(['auth'])->group(function () {
        Route::get('/', [DataDiriController::class, 'index'])->name('datadiri.index');

        // Tambahkan ini untuk AJAX update
        Route::post('/mahasiswa/update', [DataDiriController::class, 'updateMahasiswa'])->name('datadiri.mahasiswa.update');
        // Tambahkan untuk update dosen
        Route::post('/dosen/update', [DataDiriController::class, 'updateDosen'])->name('datadiri.dosen.update');
        //update tendik
        Route::post('/tendik/update', [DataDiriController::class, 'updateTendik'])->name('datadiri.tendik.update');

    });

    //rute kirim pesan
    Route::prefix('kirimpesan')->group(function () {
        Route::get('/', [KirimPesanController::class, 'index'])->name('kirimpesan.index');
        Route::post('/list', [KirimPesanController::class, 'list'])->name('kirimpesan.list');
        Route::get('{id}/form', [KirimPesanController::class, 'form'])->name('kirimpesan.form');
        Route::post('/kirim', [KirimPesanController::class, 'kirim'])->name('kirimpesan.kirim');
    });

    Route::prefix('kirimemail')->group(function () {
        Route::get('/', [KirimEmailController::class, 'index'])->name('kirimemail.index');
        Route::post('/list', [KirimEmailController::class, 'list'])->name('kirimemail.list');
        Route::get('{id}/form', [KirimEmailController::class, 'form'])->name('kirimemail.form');
        Route::post('/kirim-email', [KirimEmailController::class, 'kirim'])->name('kirimemail.kirim');
    });

});
