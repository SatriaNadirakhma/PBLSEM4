<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranModel;
use App\Models\JadwalModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PendaftaranController extends Controller
{
    public function index()
{
    $user = auth()->user();
    
    switch (strtolower($user->role)) {
        case 'mahasiswa':
            return $this->handleMahasiswa();
        case 'dosen':
            return $this->handleDosen();
        case 'tendik':
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
    

    $pernahDiterima = DB::table('pendaftaran')
        ->join('detail_pendaftaran', 'pendaftaran.pendaftaran_id', '=', 'detail_pendaftaran.pendaftaran_id')
        ->where('pendaftaran.mahasiswa_id', $mahasiswa->mahasiswa_id)
        ->where('detail_pendaftaran.status', 'diterima')
        ->exists();


    $breadcrumb = (object) [
        'title' => 'Form Pendaftaran',
        'list' => ['Home', 'Pendaftaran', 'Form Pendaftaran'],
    ];

    $pendaftaranTerakhir = PendaftaranModel::where('mahasiswa_id', $mahasiswa->mahasiswa_id)
                        ->latest()
                        ->first();

    if ($pendaftaranTerakhir && strtolower($pendaftaranTerakhir->status) === 'menunggu') {
        return redirect()->route('dashboard')->with('warning', 'Anda sudah mendaftar. Silakan tunggu verifikasi dari admin.');
    }

    $data = [
        'mahasiswa' => $mahasiswa->load('prodi.jurusan.kampus'),
        'jadwalList' => JadwalModel::select('jadwal_id', 'tanggal_pelaksanaan', 'jam_mulai')->get(),
        'pernahDiterima' => $pernahDiterima,
        'itc_link' => env('ITC_LINK_MAHASISWA', 'https://itc-indonesia.com/mahasiswa-upgrade'),
        'breadcrumb' => $breadcrumb,
        'activeMenu' => 'pendaftaran',
        'pendaftaran' => $pendaftaranTerakhir,
    ];

   // Jika sudah pernah diterima, arahkan ke pendaftaran berbayar
    if ($pernahDiterima) {
        return view('pendaftaran.mahasiswa_berbayar', $data);
    }
    // Jika belum, arahkan ke pendaftaran gratis
    return view('pendaftaran.mahasiswa_gratis', $data);
}

protected function handleDosen()
{
    $breadcrumb = (object) [
        'title' => 'Pendaftaran Program Dosen',
        'list' => ['Home', 'Pendaftaran', 'Dosen'],
    ];

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

    // Cek pendaftaran terakhir
    $lastPendaftaran = PendaftaranModel::where('mahasiswa_id', $mahasiswaId)
        ->latest()
        ->first();

    if ($lastPendaftaran) {
        $lastStatus = DB::table('detail_pendaftaran')
            ->where('pendaftaran_id', $lastPendaftaran->pendaftaran_id)
            ->latest('updated_at')
            ->value('status');

        if (in_array($lastStatus, ['menunggu', 'diterima'])) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mendaftar ulang sebelum status pendaftaran sebelumnya selesai.'
            ], 403);
        }
    }

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
