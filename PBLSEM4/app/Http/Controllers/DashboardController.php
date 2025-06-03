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
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index()
    {
        // ... (Kode method index() Anda yang sudah ada) ...
        // Pastikan Anda mem-pass 'user' dan 'dataPendaftarPerBulan' ke view
        // seperti yang sudah Anda lakukan di kode asli Anda.

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
            if ($data->bulan >= 1 && $data->bulan <= 12) {
                $dataPendaftarPerBulan[$data->bulan - 1] = $data->total;
            }
        }

        $informasi = collect(); // Default kosong

        if ($user && $user->role === 'mahasiswa') {
            $informasi = InformasiModel::latest()->get(); // ambil semua informasi, urutkan terbaru dulu
        }

        // dd($dataPendaftarPerBulan, $jumlah_lolos, $jumlah_tidak_lolos, $user->role);
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
            'user', // Pastikan $user di-pass untuk cek role di blade
            'dataPendaftarPerBulan',
            'informasi'
        ));
    }

    public function chartDataStream(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized access to real-time data stream.');
        }

        // Set timeout eksekusi skrip (misalnya 0 untuk tak terbatas, atau nilai tinggi)
        set_time_limit(0);
        // Abaikan abort oleh user jika koneksi ditutup (penting untuk SSE)
        ignore_user_abort(true);

        $response = new StreamedResponse(function () {
            // Nonaktifkan buffering output PHP di awal loop
            // Hapus semua buffer output yang mungkin ada sebelum memulai loop SSE
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Aktifkan buffering output baru untuk SSE
            // ob_start(); // Tidak perlu ini jika kita langsung flush setiap kali

            while (true) {
                // Cek apakah koneksi masih hidup
                if (connection_aborted()) {
                    break; // Keluar dari loop jika koneksi terputus
                }

                // --- Ambil Data Statistik TERBARU dari Database ---
                $currentYear = Carbon::now()->year;
                $pendaftarPerBulan = PendaftaranModel::select(
                    DB::raw('MONTH(created_at) as bulan'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereYear('created_at', $currentYear)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

                $dataPendaftarPerBulan = array_fill(0, 12, 0);
                foreach ($pendaftarPerBulan as $data) {
                    if ($data->bulan >= 1 && $data->bulan <= 12) {
                        $dataPendaftarPerBulan[$data->bulan - 1] = $data->total;
                    }
                }

                $jumlahLolos = HasilUjianModel::where('status_lulus', 'lulus')->count();
                $jumlahTidakLolos = HasilUjianModel::where('status_lulus', 'tidak lulus')->count();

                $jumlah_user_realtime = UserModel::count();
                $jumlah_admin_realtime = AdminModel::count();
                $jumlah_mahasiswa_realtime = MahasiswaModel::count();
                $jumlah_dosen_realtime = DosenModel::count();
                $jumlah_tendik_realtime = TendikModel::count();

                $status_menunggu_realtime = DetailPendaftaranModel::where('status', 'menunggu')->count();
                $status_diterima_realtime = DetailPendaftaranModel::where('status', 'diterima')->count();
                $status_ditolak_realtime = DetailPendaftaranModel::where('status', 'ditolak')->count();
                $jumlah_pendaftar_realtime = $status_menunggu_realtime + $status_diterima_realtime + $status_ditolak_realtime;


                $realtimeData = [
                    'lineChartData' => $dataPendaftarPerBulan,
                    'pieChartData' => [
                        'lolos' => $jumlahLolos,
                        'tidakLolos' => $jumlahTidakLolos,
                    ],
                    'cardData' => [
                        'jumlah_user' => $jumlah_user_realtime,
                        'jumlah_admin' => $jumlah_admin_realtime,
                        'jumlah_mahasiswa' => $jumlah_mahasiswa_realtime,
                        'jumlah_dosen' => $jumlah_dosen_realtime,
                        'jumlah_tendik' => $jumlah_tendik_realtime,
                        'jumlah_pendaftar' => $jumlah_pendaftar_realtime,
                        'status_menunggu' => $status_menunggu_realtime,
                        'status_diterima' => $status_diterima_realtime,
                        'status_ditolak' => $status_ditolak_realtime,
                        'jumlah_lolos' => $jumlahLolos,
                        'jumlah_tidak_lolos' => $jumlahTidakLolos,
                    ],
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ];

                echo "data: " . json_encode($realtimeData) . "\n\n";

                // Flush output buffer agar data segera terkirim ke browser
                // Ini mungkin perlu disesuaikan tergantung pada konfigurasi web server (Apache/Nginx)
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                // Tunggu beberapa detik sebelum mengirim data lagi
                sleep(5); // Kirim data setiap 5 detik. Sesuaikan sesuai kebutuhan.
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // Penting untuk Nginx

        return $response;
    }
}