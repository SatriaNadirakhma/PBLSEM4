<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\HasilUjianModel;
use App\Models\SuratModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request; // Pastikan Request diimport jika belum

class HasilPesertaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Hasil Ujian Anda',
            'list' => ['Home', 'Hasil Ujian'],
        ];
        
        $page = (object) [
            'title' => 'Riwayat Hasil Ujian',
        ];
        
        $activeMenu = 'hasilPeserta';
        $userId = Auth::id(); // Ambil ID user yang sedang login
        
        // Ambil data hasil ujian dengan relasi jadwal
        $hasilPeserta = HasilUjianModel::with('jadwal') // Assuming ada relasi dengan tabel jadwal
            ->where('user_id', $userId)
            ->get();
        
        $canAccessSurat = false; // Mengubah nama variabel karena sekarang untuk akses (preview)
        $countNotLulus = $hasilPeserta->where('status_lulus', 'tidak lulus')->count();

        if ($countNotLulus >= 2) {
            $canAccessSurat = true;
        }

        // Mengambil surat keterangan spesifik dengan surat_id = 1
        $suratKeterangan = SuratModel::find(1); 
        
        return view('hasilPeserta.index', compact('breadcrumb', 'page', 'activeMenu', 'hasilPeserta', 'canAccessSurat', 'suratKeterangan'));
    }

    /**
     * Metode baru untuk menampilkan pratinjau surat keterangan di tab baru.
     */
    public function PreviewSuratKeterangan() // Mengubah nama metode
    {
        $userId = Auth::id();
        $hasilPeserta = HasilUjianModel::where('user_id', $userId)->get();
        $countNotLulus = $hasilPeserta->where('status_lulus', 'tidak lulus')->count();

        // Periksa lagi kondisi di server-side untuk keamanan
        if ($countNotLulus < 2) {
            // Jika tidak memenuhi kriteria, tampilkan error atau abort
            // Menggunakan abort(403) agar lebih konsisten untuk akses file
            abort(403, 'Maaf, Anda belum memenuhi kriteria untuk melihat file ini.');
            // Atau jika ingin redirect dengan pesan:
            // return redirect()->route('hasilPeserta.index')->with('error', 'Maaf, Anda belum memenuhi kriteria untuk melihat file ini.');
        }

        // Mengambil surat keterangan spesifik dengan surat_id = 1
        $suratKeterangan = SuratModel::find(1);
        
        // Periksa apakah surat ditemukan dan file ada
        if (!$suratKeterangan || !Storage::disk('public')->exists($suratKeterangan->file_path)) {
            abort(404, 'File surat keterangan tidak ditemukan.');
        }

        // Mengembalikan respons untuk menampilkan PDF di browser
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $suratKeterangan->file_name . '"', // 'inline' untuk preview
        ];

        return Storage::disk('public')->response($suratKeterangan->file_path, $suratKeterangan->file_name, $headers);
    }
}