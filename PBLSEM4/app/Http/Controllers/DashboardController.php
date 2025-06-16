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
use App\Models\JurusanModel; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    /**
     * Method privat untuk mengambil data statistik dashboard.
     */
    private function getDashboardStatistics()
    {
        // --- DATA UNTUK BAR CHART (QUERY DIPERBARUI) ---
        // PERBAIKAN: Menambahkan join melalui tabel 'prodi'
        $barChartData = JurusanModel::query()
            ->select(
                'jurusan.jurusan_nama',
                // Gunakan COALESCE untuk mengubah nilai NULL (jurusan tanpa data) menjadi 0
                DB::raw('COALESCE(AVG(hasil_ujian.nilai_total), 0) as rata_rata_nilai')
            )
            ->leftJoin('prodi', 'jurusan.jurusan_id', '=', 'prodi.jurusan_id')
            ->leftJoin('mahasiswa', 'prodi.prodi_id', '=', 'mahasiswa.prodi_id')
            ->leftJoin('user', 'mahasiswa.mahasiswa_id', '=', 'user.mahasiswa_id')
            ->leftJoin('hasil_ujian', 'user.user_id', '=', 'hasil_ujian.user_id')
            ->groupBy('jurusan.jurusan_id', 'jurusan.jurusan_nama') // Group by ID dan Nama Jurusan
            ->orderBy('jurusan.jurusan_nama', 'asc')
            ->get();

        // --- PERBAIKAN UTAMA: Memformat label menjadi multi-baris ---
        $barChartLabels = $barChartData->pluck('jurusan_nama')->map(function ($label) {
            // Memecah nama jurusan berdasarkan spasi menjadi sebuah array.
            // Contoh: "Teknologi Informasi" menjadi ["Teknologi", "Informasi"]
            // Chart.js akan otomatis merender array ini menjadi beberapa baris.
            return explode(' ', $label);
        })->toArray();

         $barChartValues = $barChartData->pluck('rata_rata_nilai')->map(function ($value) {
            return round($value, 2);
        })->toArray();


        // --- DATA UNTUK PIE CHART (TETAP) ---
        $jumlahLolos = HasilUjianModel::where('status_lulus', 'lulus')->count();
        $jumlahTidakLolos = HasilUjianModel::where('status_lulus', 'tidak lulus')->count();

        // --- DATA UNTUK KARTU STATISTIK (TETAP) ---
        $jumlah_user = UserModel::count();
        $jumlah_admin = AdminModel::count();
        $jumlah_mahasiswa = MahasiswaModel::count();
        $jumlah_dosen = DosenModel::count();
        $jumlah_tendik = TendikModel::count();
        $status_menunggu = DetailPendaftaranModel::where('status', 'menunggu')->count();
        $status_diterima = DetailPendaftaranModel::where('status', 'diterima')->count();
        $status_ditolak = DetailPendaftaranModel::where('status', 'ditolak')->count();
        $jumlah_pendaftar = $status_menunggu + $status_diterima + $status_ditolak;

        // --- MENGEMBALIKAN SEMUA DATA DALAM SATU ARRAY ---
        return [
            'barChart' => [
                'labels' => $barChartLabels,
                'values' => $barChartValues,
            ],
            'pieChartData' => [
                'lolos' => $jumlahLolos,
                'tidakLolos' => $jumlahTidakLolos,
            ],
            'cardData' => [
                'jumlah_user' => $jumlah_user,
                'jumlah_admin' => $jumlah_admin,
                'jumlah_mahasiswa' => $jumlah_mahasiswa,
                'jumlah_dosen' => $jumlah_dosen,
                'jumlah_tendik' => $jumlah_tendik,
                'jumlah_pendaftar' => $jumlah_pendaftar,
                'status_menunggu' => $status_menunggu,
                'status_diterima' => $status_diterima,
                'status_ditolak' => $status_ditolak,
                'jumlah_losos' => $jumlahLolos,
                'jumlah_tidak_lolos' => $jumlahTidakLolos,
            ],
            'timestamp' => Carbon::now()->toDateTimeString(),
        ];
    }

    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Selamat Datang di SIPINTA👋',
            'list' => ['Home', 'Dashboard']
        ];
        $activeMenu = 'dashboard';
        $user = auth()->user();
        
        $dashboardData = $this->getDashboardStatistics();

        $informasi = collect();
        if ($user && in_array($user->role, ['mahasiswa', 'dosen', 'tendik'])) {
            $informasi = InformasiModel::latest()->get();
        }
        
        return view('welcome', [
            'breadcrumb' => $breadcrumb,
            'activeMenu' => $activeMenu,
            'user' => $user,
            'informasi' => $informasi,
            'jumlah_user' => $dashboardData['cardData']['jumlah_user'],
            'jumlah_admin' => $dashboardData['cardData']['jumlah_admin'],
            'jumlah_mahasiswa' => $dashboardData['cardData']['jumlah_mahasiswa'],
            'jumlah_dosen' => $dashboardData['cardData']['jumlah_dosen'],
            'jumlah_tendik' => $dashboardData['cardData']['jumlah_tendik'],
            'status_menunggu' => $dashboardData['cardData']['status_menunggu'],
            'status_diterima' => $dashboardData['cardData']['status_diterima'],
            'status_ditolak' => $dashboardData['cardData']['status_ditolak'],
            'jumlah_pendaftar' => $dashboardData['cardData']['jumlah_pendaftar'],
            'jumlah_lolos' => $dashboardData['pieChartData']['lolos'],
            'jumlah_tidak_lolos' => $dashboardData['pieChartData']['tidakLolos'],
            'barChartLabels' => $dashboardData['barChart']['labels'],
            'barChartValues' => $dashboardData['barChart']['values'],
        ]);
    }

  public function chartDataStream(Request $request)
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized access to real-time data stream.');
        }

        set_time_limit(0);
        ignore_user_abort(true);

        $response = new StreamedResponse(function () {
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            // Variabel untuk melacak kapan terakhir kali data dikirim
            $lastRun = 0;
            // Interval pengiriman data dalam detik
            $interval = 5; 

            // Loop tak terbatas untuk koneksi SSE
            while (true) {
                // PERBAIKAN UTAMA 1: Cek apakah koneksi diputus oleh user
                // Jika ya, langsung hentikan loop tanpa menunggu.
                if (connection_aborted()) {
                    break;
                }
                
                // PERBAIKAN UTAMA 2: Hanya ambil data dan kirim jika sudah waktunya
                // Ini mencegah query database yang berlebihan setiap detik.
                if ((time() - $lastRun) >= $interval) {
                    $realtimeData = $this->getDashboardStatistics();
                    echo "data: " . json_encode($realtimeData) . "\n\n";

                    // Pastikan data langsung terkirim
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();

                    // Update waktu terakhir pengiriman data
                    $lastRun = time();
                }

                // PERBAIKAN UTAMA 3: Tidur hanya 1 detik
                // Ini membuat loop lebih sering memeriksa `connection_aborted()`
                // sehingga server bisa lebih cepat tahu jika user meninggalkan halaman.
                sleep(1);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}
