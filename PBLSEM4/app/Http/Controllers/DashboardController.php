<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\PendaftaranModel;
use App\Models\DetailPendaftaranModel;
use App\Models\AdminModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\TendikModel;
use App\Models\HasilUjianModel;
use App\Models\InformasiModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Breadcrumb dan menu aktif
        $breadcrumb = (object)[
            'title' => 'Selamat Datang di SIPINTA👋',
            'list' => ['Home', 'Dashboard']
        ];

        $activeMenu = 'dashboard';

        $user = auth()->user();

        // Statistik user
        $jumlah_user = UserModel::count();
        $jumlah_admin = AdminModel::count();
        $jumlah_mahasiswa = MahasiswaModel::count();
        $jumlah_dosen = DosenModel::count();
        $jumlah_tendik = TendikModel::count();

        // Inisialisasi statistik pendaftaran dan hasil ujian
        $status_menunggu = 0;
        $status_diterima = 0;
        $status_ditolak = 0;
        $jumlah_pendaftar = 0;
        $jumlah_lolos = 0;
        $jumlah_tidak_lolos = 0;

        if ($user && $user->role === 'admin') {
            // Hitung status pendaftaran
            $status_menunggu = DetailPendaftaranModel::where('status', 'menunggu')->count();
            $status_diterima = DetailPendaftaranModel::where('status', 'diterima')->count();
            $status_ditolak = DetailPendaftaranModel::where('status', 'ditolak')->count();

            $jumlah_pendaftar = $status_menunggu + $status_diterima + $status_ditolak;

            // Hitung hasil ujian
            $jumlah_lolos = HasilUjianModel::where('status_lulus', 'lulus')->count();
            $jumlah_tidak_lolos = HasilUjianModel::where('status_lulus', 'tidak lulus')->count();
        }

        // Ambil data pendaftar per bulan (12 bulan) untuk tahun berjalan
        $currentYear = Carbon::now()->year;

        $pendaftarPerBulan = PendaftaranModel::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->whereYear('created_at', $currentYear)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        // Siapkan array 12 bulan dengan default 0
        $dataPendaftarPerBulan = array_fill(0, 12, 0);

        foreach ($pendaftarPerBulan as $data) {
            // Pastikan bulan valid antara 1-12
            if ($data->bulan >= 1 && $data->bulan <= 12) {
                $dataPendaftarPerBulan[$data->bulan - 1] = $data->total;
            }
        }

        $informasi = collect(); // Default kosong

        if ($user && $user->role === 'mahasiswa') {
            $informasi = InformasiModel::latest()->get(); // ambil semua informasi, urutkan terbaru dulu
        }

        // Kirim semua data ke view 'welcome'
        return view('welcome', compact(
            'breadcrumb',
            'activeMenu',
            'jumlah_user',
            'jumlah_admin',
            'jumlah_mahasiswa',
            'jumlah_dosen',
            'jumlah_tendik',
            'status_menunggu',
            'status_diterima',
            'status_ditolak',
            'jumlah_pendaftar',
            'jumlah_lolos',
            'jumlah_tidak_lolos',
            'user',
            'dataPendaftarPerBulan',
              'informasi'

        ));
    }
}
