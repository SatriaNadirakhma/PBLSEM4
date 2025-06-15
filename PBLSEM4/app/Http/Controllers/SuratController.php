<?php

namespace App\Http\Controllers;

use App\Models\SuratModel; // Import model SuratModel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen surat (untuk admin).
     */
    public function adminIndex()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Surat',
            'list' => ['Home', 'Surat'],
        ];

        $page = (object) [
            'title' => 'Kelola file surat yang akan diakses oleh peserta.',
        ];

        $activeMenu = 'KelolaSurat'; // Sesuaikan active menu

        $surats = SuratModel::all(); // Mengambil semua surat, karena bisa banyak

        return view('surat.admin_index', compact('breadcrumb', 'page', 'activeMenu', 'surats'));
    }

    /**
     * Menampilkan form untuk menambah surat baru.
     */
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Surat Baru',
            'list' => ['Home', 'Surat', 'Tambah'],
        ];

        $page = (object) [
            'title' => 'Form untuk menambahkan surat baru.',
        ];

        $activeMenu = 'KelolaSurat';

        return view('surat.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    /**
     * Menyimpan surat baru ke database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surat_pdf' => 'required|mimes:pdf|max:10240', // Maksimal 10MB
            'judul_surat' => 'required|string|max:255',
        ], [
            'surat_pdf.required' => 'File PDF surat wajib diunggah.',
            'surat_pdf.mimes'    => 'File surat harus berformat PDF.',
            'surat_pdf.max'      => 'Ukuran file PDF tidak boleh melebihi 10MB.',
            'judul_surat.required' => 'Judul surat wajib diisi.',
            'judul_surat.string' => 'Judul surat harus berupa teks.',
            'judul_surat.max'    => 'Judul surat tidak boleh melebihi 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Unggah file baru
        $file = $request->file('surat_pdf');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'surat_files/' . $fileName; // Folder penyimpanan di dalam disk 'public'

        // Simpan ke disk 'public'
        Storage::disk('public')->put($filePath, file_get_contents($file));

        // Simpan data di database
        SuratModel::create([
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'judul_surat' => $request->judul_surat,
        ]);

        return redirect()->route('surat.admin.index')->with('success', 'Surat berhasil diunggah.');
    }

    /**
     * Menghapus file surat.
     */
    public function destroy(SuratModel $surat)
    {
        // Hapus file dari disk 'public'
        if (Storage::disk('public')->exists($surat->file_path)) {
            Storage::disk('public')->delete($surat->file_path);
        }

        // Hapus data dari database
        $surat->delete();

        return redirect()->route('surat.admin.index')->with('success', 'Surat berhasil dihapus.');
    }

    /**
     * Menampilkan preview PDF surat berdasarkan ID.
     */
    public function show(SuratModel $surat)
    {
        // Check keberadaan file di disk 'public'
        if (!$surat || !Storage::disk('public')->exists($surat->file_path)) {
            abort(404, 'File surat tidak ditemukan.');
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $surat->file_name . '"',
        ];

        return Storage::disk('public')->response($surat->file_path, $surat->file_name, $headers);
    }

    /**
     * Menampilkan daftar surat untuk user peserta.
     */
    public function userIndex()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Surat',
            'list' => ['Home', 'Surat'],
        ];

        $page = (object) [
            'title' => 'Daftar surat yang dapat diakses.',
        ];

        $activeMenu = 'Surat';

        $surats = SuratModel::all(); // Ambil semua surat

        return view('surat.user_index', compact('breadcrumb', 'page', 'activeMenu', 'surats'));
    }
}