<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranModel;
use App\Models\JadwalModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache; // Import Cache Facade
use Illuminate\Support\Facades\Config; // Import Config Facade

class PendaftaranController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        switch (strtolower($user->role)) {
            case 'mahasiswa':
                return $this->handleMahasiswa();
            case 'dosen':
                // Dosen selalu bisa mengakses pendaftaran mereka, tidak terpengaruh status global
                return $this->handleDosen();
            case 'tendik':
                // Tendik selalu bisa mengakses pendaftaran mereka, tidak terpengaruh status global
                return $this->handleTendik();
            default:
                abort(403, 'Akses pendaftaran tidak tersedia untuk role Anda');
        }
    }

    protected function handleMahasiswa()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('datadiri.mahasiswa')->with('error', 'Lengkapi profil mahasiswa terlebih dahulu');
        }
        
        // Cek apakah mahasiswa ini sudah pernah diterima (pendaftaran berbayar)
        $pernahDiterima = DB::table('pendaftaran')
            ->join('detail_pendaftaran', 'pendaftaran.pendaftaran_id', '=', 'detail_pendaftaran.pendaftaran_id')
            ->where('pendaftaran.mahasiswa_id', $mahasiswa->mahasiswa_id)
            ->where('detail_pendaftaran.status', 'diterima')
            ->exists();

        // Cek pendaftaran terakhir mahasiswa ini
        $pendaftaranTerakhir = PendaftaranModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                                 ->latest()
                                 ->first();

        // Ambil status terakhir pendaftaran jika ada
        $lastPendaftaranStatus = $pendaftaranTerakhir->detail->status ?? null;

        // 2. Cek status pendaftaran global dari admin
        $registrationOpen = Cache::rememberForever('registration_status', function () {
            return Config::get('app_settings.registration_open') ? 'open' : 'closed';
        }) === 'open';


        // LOGIKA UNTUK MENAMPILKAN HALAMAN PENDAFTARAN DITUTUP (hanya berlaku untuk mahasiswa GRATIS yang belum/gagal mendaftar)
        // Ini akan berlaku jika:
        // - Pendaftaran global DITUTUP
        // - Mahasiswa BELUM PERNAH DITERIMA (gratis)
        // - Mahasiswa BELUM PERNAH MENDAFTAR SAMA SEKALI (pendaftaranTerakhir null)
        // - ATAU Mahasiswa PERNAH MENDAFTAR TAPI DITOLAK (lastPendaftaranStatus 'ditolak')
        if (
            !$registrationOpen && // Pendaftaran global CLOSED
            !$pernahDiterima && // Mahasiswa belum pernah diterima (berarti gratis)
            ($pendaftaranTerakhir === null || $lastPendaftaranStatus === 'ditolak') // Belum pernah daftar ATAU sudah ditolak
        ) {
            return view('pendaftaran.mahasiswa_closed_registration', [
                'breadcrumb' => (object) [
                    'title' => 'Form Pendaftaran',
                    'list' => ['Home', 'Pendaftaran', 'Form Pendaftaran'],
                ],
                'activeMenu' => 'pendaftaran',
            ]);
        }

        // Jika sampai sini, berarti pendaftaran dibuka ATAU mahasiswa sudah diterima ATAU mahasiswa sedang menunggu/ditolak
        // kita akan menampilkan form pendaftaran, tetapi validasi submit akan menghandle status 'menunggu'
        
        $breadcrumb = (object) [
            'title' => 'Form Pendaftaran',
            'list' => ['Home', 'Pendaftaran', 'Form Pendaftaran'],
        ];

        $data = [
            'mahasiswa' => $mahasiswa->load('prodi.jurusan.kampus'),
            'jadwalList' => JadwalModel::select('jadwal_id', 'tanggal_pelaksanaan', 'jam_mulai')->get(),
            'pernahDiterima' => $pernahDiterima,
            'itc_link' => env('ITC_LINK_MAHASISWA', 'https://itc-indonesia.com/mahasiswa-upgrade'),
            'breadcrumb' => $breadcrumb,
            'activeMenu' => 'pendaftaran',
            'pendaftaran' => $pendaftaranTerakhir, // ini bisa null jika belum pernah daftar
        ];

        if ($pernahDiterima) {
            return view('pendaftaran.mahasiswa_berbayar', $data);
        }
        
        // Mahasiswa gratis (belum pernah diterima)
        // Jika status terakhir adalah 'menunggu', dia tetap akan melihat form
        // Tapi notifikasi akan muncul saat submit (dihandle di store_ajax)
        return view('pendaftaran.mahasiswa_gratis', $data);
    }

    protected function handleDosen()
    {
        $breadcrumb = (object) [
            'title' => 'Pendaftaran Program Dosen',
            'list' => ['Home', 'Pendaftaran', 'Dosen'],
        ];

        // Dosen tidak terpengaruh oleh status pendaftaran global
        return view('pendaftaran.berbayar', [
            'role' => 'dosen',
            'itc_link' => env('ITC_LINK_DOSEN', 'https://itc-indonesia.com/program-dosen'),
            'breadcrumb' => $breadcrumb,
            'activeMenu' => 'pendaftaran',
        ]);
    }

    protected function handleTendik()
    {
        $breadcrumb = (object) [
            'title' => 'Pendaftaran Program Tendik',
            'list' => ['Home', 'Pendaftaran', 'Tendik'],
        ];

        // Tendik tidak terpengaruh oleh status pendaftaran global
        return view('pendaftaran.berbayar', [
            'role' => 'tendik',
            'itc_link' => env('ITC_LINK_TENDIK', 'https://itc-indonesia.com/program-tendik'),
            'breadcrumb' => $breadcrumb,
            'activeMenu' => 'pendaftaran',
        ]);
    }

    public function store_ajax(Request $request)
    {
        $mahasiswaId = $request->mahasiswa_id;
        $user = auth()->user(); 

        // Cek pendaftaran terakhir mahasiswa ini
        $lastPendaftaran = PendaftaranModel::where('mahasiswa_id', $mahasiswaId)
            ->latest()
            ->first();

        // 1. Cek apakah mahasiswa ini sudah pernah diterima (pendaftaran berbayar)
        $pernahDiterima = DB::table('pendaftaran')
            ->join('detail_pendaftaran', 'pendaftaran.pendaftaran_id', '=', 'detail_pendaftaran.pendaftaran_id')
            ->where('pendaftaran.mahasiswa_id', $mahasiswaId)
            ->where('detail_pendaftaran.status', 'diterima')
            ->exists();

        // 2. Cek status pendaftaran global dari admin
        $registrationOpen = Cache::get('registration_status') === 'open';

        // Ambil status terakhir pendaftaran jika ada
        $lastPendaftaranStatus = $lastPendaftaran->detail->status ?? null;


        // LOGIKA PENCEGAHAN SUBMIT FORM (berlaku untuk mahasiswa)
        if (strtolower($user->role) === 'mahasiswa') {
            // Jika status terakhir 'menunggu', langsung berikan notifikasi
            if ($lastPendaftaran && $lastPendaftaranStatus === 'menunggu') {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mendaftar. Silakan tunggu verifikasi dari admin.'
                ], 403);
            }
            // Jika pendaftaran global CLOSED DAN mahasiswa gratis yang BELUM PERNAH DITERIMA DAN (belum pernah daftar SAMA SEKALI ATAU status ditolak)
            else if (!$registrationOpen && !$pernahDiterima && ($lastPendaftaran === null || $lastPendaftaranStatus === 'ditolak')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran untuk mahasiswa gratis sedang ditutup oleh admin.'
                ], 403);
            }
        }
        
        // --- Lanjutkan validasi dan proses penyimpanan jika lolos dari semua kondisi di atas ---

        $request->validate([
            'scan_ktp' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'scan_ktm' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'mahasiswa_id' => 'required|exists:mahasiswa,mahasiswa_id',
            'jadwal_id' => 'required|exists:jadwal,jadwal_id',
        ]);

        try {
            DB::beginTransaction();

            $lastId = PendaftaranModel::max('pendaftaran_id') ?? 0;
            $nextNumber = $lastId + 1;
            $kode = 'PT' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            $scan_ktp = $request->file('scan_ktp')->store('pendaftaran/scan_ktp', 'public');
            $scan_ktm = $request->file('scan_ktm')->store('pendaftaran/scan_ktm', 'public');
            $pas_foto = $request->file('pas_foto')->store('pendaftaran/pas_foto', 'public');

            $pendaftaran = PendaftaranModel::create([
                'pendaftaran_kode' => $kode,
                'tanggal_pendaftaran' => now(),
                'scan_ktp' => $scan_ktp,
                'scan_ktm' => $scan_ktm,
                'pas_foto' => $pas_foto,
                'mahasiswa_id' => $request->mahasiswa_id,
                'jadwal_id' => $request->jadwal_id,
            ]);

            // Insert ke detail_pendaftaran dengan status 'menunggu'
            DB::table('detail_pendaftaran')->insert([
                'pendaftaran_id' => $pendaftaran->pendaftaran_id,
                'status' => 'menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Pendaftaran Anda berhasil. Silakan periksa email Anda secara berkala untuk konfirmasi dan informasi selanjutnya dari admin.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error pendaftaran: ' . $e->getMessage(), [
                'mahasiswa_id' => $request->mahasiswa_id,
                'jadwal_id' => $request->jadwal_id,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => app()->environment('local') ? $e->getMessage() : 'Terjadi kesalahan saat menyimpan data.'
            ], 500);
        }
    }
}