<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;                     // ← Pastikan sudah di‐import
use App\Models\PendaftaranModel;
use App\Mail\InformasiMahasiswaMail;
use Yajra\DataTables\DataTables;

class KirimEmailController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Kirim Email',
            'list'  => ['Home', 'Kirim Email'],
        ];

        $page = (object)[
            'title' => 'Kirim informasi Email ke mahasiswa yang sudah diverifikasi',
        ];

        $activeMenu = 'kirimemail';

        return view('kirimemail.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $data = PendaftaranModel::with(['mahasiswa', 'detail'])
            ->whereHas('detail', function ($query) use ($request) {
                $query->whereIn('status', ['diterima', 'ditolak']);
                if ($request->status_filter) {
                    $query->where('status', $request->status_filter);
                }
            });

        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $data->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($q2) use ($search) {
                    $q2->where('nim', 'like', "%$search%")
                       ->orWhere('mahasiswa_nama', 'like', "%$search%");
                });
            });
        }

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('nim', fn($r) => $r->mahasiswa->nim ?? '')
            ->addColumn('nama', fn($r) => $r->mahasiswa->mahasiswa_nama ?? '')
            ->addColumn('tanggal_daftar', fn($r) => date('d-m-Y', strtotime($r->tanggal_pendaftaran)))
            ->addColumn('no_telp', fn($r) => $r->mahasiswa->no_telp ?? '-')
            ->addColumn('status', fn($r) => ucfirst(optional($r->detail_terakhir)->status))
            ->addColumn('aksi', function ($r) {
                return '
                    <button onclick="modalAction(\'' . url('kirimemail/' . $r->pendaftaran_id . '/form') . '\')" 
                        class="btn btn-primary btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                        <i class="fas fa-envelope me-1"></i> Kirim Email
                    </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function form($id)
    {
        $data = PendaftaranModel::with('mahasiswa')->findOrFail($id);
        return view('kirimemail.form', compact('data'));
    }

    /**
     * method "kirim" sekarang kita gunakan untuk SMTP Mail Laravel
     */
    public function kirim(Request $request)
    {
        // 1) Validasi input: pendaftaran_id, subject, pesan
        $request->validate([
            'pendaftaran_id' => 'required|integer|exists:pendaftaran,pendaftaran_id',
            'subject'        => 'required|string|max:255',
            'pesan'          => 'required|string',
        ]);

        // 2) Ambil data pendaftaran beserta relasi mahasiswa
        $pendaftaran = PendaftaranModel::with('mahasiswa.user')->findOrFail($request->pendaftaran_id);

        $emailTujuan   = $pendaftaran->mahasiswa->user->email ?? '-';
        $namaMahasiswa = $pendaftaran->mahasiswa->mahasiswa_nama ?? '-';
        $nimMahasiswa  = $pendaftaran->mahasiswa->nim ?? '-';

        if (! $emailTujuan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tidak ditemukan alamat email mahasiswa.'
            ], 422);
        }

        // 3) Siapkan data untuk Mailable
        $dataMail = [
            'nama'  => $namaMahasiswa,
            'nim'   => $nimMahasiswa,
            'pesan' => $request->pesan,
        ];

        try {
            Mail::to($emailTujuan)
                ->send((new InformasiMahasiswaMail($dataMail))
                ->subject($request->subject)
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Email berhasil dikirim ke ' . $emailTujuan,
        ]);

        } catch (\Exception $e) {
            Log::error('Exception Mail: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan saat mengirim email: ' . $e->getMessage(),
            ], 500);
        }
    }
}
