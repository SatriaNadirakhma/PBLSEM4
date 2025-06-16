<?php

namespace App\Http\Controllers;

use App\Models\PanduanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PanduanController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen panduan (untuk admin).
     */
    public function adminIndex()
    {
        $breadcrumb = (object) [
            'title' => 'Kelola Panduan Peserta',
            'list' => ['Home', 'Panduan'],
        ];

        $page = (object) [
            'title' => 'Kelola file panduan TOEIC yang akan diakses oleh peserta.',
        ];


        $activeMenu = 'KelolaPanduan';

        $panduan = PanduanModel::first();

        return view('panduan.admin_index', compact('breadcrumb', 'page', 'activeMenu', 'panduan'));
    }

    /**
     * Mengunggah atau mengganti file panduan.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'panduan_pdf' => 'required|mimes:pdf|max:10240', // Maksimal 10MB
        ], [
            'panduan_pdf.required' => 'File PDF panduan wajib diunggah.',
            'panduan_pdf.mimes'    => 'File panduan harus berformat PDF.',
            'panduan_pdf.max'      => 'Ukuran file PDF tidak boleh melebihi 10MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $panduan = PanduanModel::first();

        // Jika sudah ada panduan sebelumnya, hapus file lama dari disk 'public'
        // *** PERBAIKAN DI SINI: Tambahkan ->disk('public') ***
        if ($panduan && Storage::disk('public')->exists($panduan->file_path)) {
            Storage::disk('public')->delete($panduan->file_path);
        }

        // Unggah file baru
        $file = $request->file('panduan_pdf');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'panduan_files/' . $fileName; // Folder penyimpanan di dalam disk 'public'

        // Simpan ke disk 'public'
        // *** PERBAIKAN DI SINI: Tambahkan ->disk('public') ***
        Storage::disk('public')->put($filePath, file_get_contents($file));

        // Simpan atau update data di database
        if ($panduan) {
            $panduan->update([
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
            ]);
        } else {
            PanduanModel::create([
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()->back()->with('success', 'Panduan berhasil diunggah/diganti.');
    }

    /**
     * Menampilkan preview PDF panduan.
     */
    public function show()
    {
        $panduan = PanduanModel::first();

        // Check keberadaan file di disk 'public'
        // *** PERBAIKAN DI SINI: Tambahkan ->disk('public') ***
        if (!$panduan || !Storage::disk('public')->exists($panduan->file_path)) {
            abort(404, 'File panduan tidak ditemukan.');
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $panduan->file_name . '"',
        ];

        // Return response dari disk 'public'
        // *** PERBAIKAN DI SINI: Tambahkan ->disk('public') ***
        return Storage::disk('public')->response($panduan->file_path, $panduan->file_name, $headers);
    }
}