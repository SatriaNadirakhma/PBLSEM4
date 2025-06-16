<?php

namespace App\Http\Controllers;

use App\Models\SuratModel; // Import model SuratModel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Penting untuk validasi

class SuratController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen surat keterangan (untuk admin).
     * Hanya akan menampilkan satu surat (surat_id = 1).
     */
    public function adminIndex()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Surat Keterangan', // Sesuaikan judul
            'list' => ['Home', 'Surat Keterangan'],
        ];

        $page = (object) [
            'title' => 'Kelola file surat keterangan yang akan diakses oleh Mahasiswa.',
        ];

        $activeMenu = 'KelolaSurat'; // Sesuaikan active menu

        // Mengambil hanya satu surat keterangan dengan ID 1
        $suratKeterangan = SuratModel::find(1); 

        return view('surat.admin_index', compact('breadcrumb', 'page', 'activeMenu', 'suratKeterangan'));
    }

    /**
     * Mengunggah atau mengganti file surat keterangan.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surat_pdf' => 'required|mimes:pdf|max:10240', // Maksimal 10MB
        ], [
            'surat_pdf.required' => 'File PDF surat wajib diunggah.',
            'surat_pdf.mimes'    => 'File surat harus berformat PDF.',
            'surat_pdf.max'      => 'Ukuran file PDF tidak boleh melebihi 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Ambil surat keterangan yang sudah ada (dengan ID 1)
        $suratKeterangan = SuratModel::find(1);

        // Hapus file lama jika ada dan surat_keterangan sudah ada
        if ($suratKeterangan && Storage::disk('public')->exists($suratKeterangan->file_path)) {
            Storage::disk('public')->delete($suratKeterangan->file_path);
        }

        // Unggah file baru
        $file = $request->file('surat_pdf');
        // Buat nama file unik, misalnya menggunakan waktu saat ini
        $fileName = 'surat_keterangan_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = 'surat_files/' . $fileName; // Folder penyimpanan di dalam disk 'public'

        // Simpan file baru ke disk 'public'
        Storage::disk('public')->put($filePath, file_get_contents($file));

        // Perbarui atau buat data di database untuk surat_id = 1
        if ($suratKeterangan) {
            // Jika surat sudah ada, update datanya
            $suratKeterangan->update([
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'judul_surat' => 'Surat Keterangan Utama', // Anda bisa mengunci judul ini
            ]);
        } else {
            // Jika belum ada, buat record baru dengan surat_id = 1
            // Pastikan kolom primary key 'surat_id' bisa diisi secara massal atau unset $guarded di model
            // Untuk memastikan surat_id=1, kita bisa menggunakan firstOrCreate atau langsung create jika yakin
            SuratModel::create([
                'surat_id' => 1, // Pastikan kolom ini diisi
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'judul_surat' => 'Surat Keterangan Utama',
            ]);
        }

        return redirect()->route('surat.admin.index')->with('success', 'File surat keterangan berhasil diperbarui.');
    }

    /**
     * Menampilkan preview PDF surat berdasarkan ID.
     * Metode ini akan tetap digunakan oleh admin preview dan juga peserta.
     */
    public function show($surat_id) // Ubah parameter ke $surat_id untuk konsistensi
    {
        // Mengambil surat berdasarkan ID
        $surat = SuratModel::find($surat_id);

        // Check keberadaan file di disk 'public'
        if (!$surat || !Storage::disk('public')->exists($surat->file_path)) {
            abort(404, 'File surat tidak ditemukan.');
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $surat->file_name . '"', // 'inline' untuk preview
        ];

        return Storage::disk('public')->response($surat->file_path, $surat->file_name, $headers);
    }
}