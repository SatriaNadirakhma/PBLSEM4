<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PendaftaranModel;
use Yajra\DataTables\DataTables;

class KirimPesanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Kirim Pesan WhatsApp',
            'list' => ['Home', 'Kirim Pesan'],
        ];

        $page = (object)[
            'title' => 'Kirim pesan WhatsApp ke mahasiswa yang sudah diverifikasi',
        ];

        $activeMenu = 'kirimpesan';

        return view('kirimpesan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $data = PendaftaranModel::with(['mahasiswa', 'detail'])
            ->whereHas('detail', function ($query) use ($request) {
                $query->whereIn('status', ['diterima', 'ditolak']);

                if ($request->status_filter) {
                    $query->where('status', $request->status_filter);
                }
            })
            // Tambahkan select untuk memastikan kolom pendaftaran_id dan tanggal_pendaftaran ada
            ->select('pendaftaran.*');

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
            // *** PERUBAHAN DI SINI: Ganti 'status' menjadi 'pendaftaran_status' ***
            ->addColumn('pendaftaran_status', fn($r) => ucfirst(optional($r->detail_terakhir)->status ?? ''))
            // --- AKHIR PERUBAHAN ---
            ->addColumn('status_pengiriman', function ($r) {
                // Defaultnya 'antrean' jika belum ada status tersimpan
                return '<span id="status-pengiriman-' . $r->pendaftaran_id . '" class="badge bg-warning">Antrean</span>';
            })
            ->addColumn('aksi', function ($r) {
                return '
                    <button onclick="modalAction(\'' . url('kirimpesan/' . $r->pendaftaran_id . '/form') . '\', ' . $r->pendaftaran_id . ')"
                        class="btn btn-success btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                        <i class="fab fa-whatsapp me-1"></i> Kirim Pesan
                    </button>';
            })
            ->rawColumns(['aksi', 'status_pengiriman']) // pendaftaran_status tidak perlu raw
            ->make(true);
    }

    public function form($id)
    {
        $data = PendaftaranModel::with('mahasiswa')->findOrFail($id);
        return view('kirimpesan.form', compact('data'));
    }

    public function kirim($pendaftaranId, $nomor, $pesan)
    {
        $response = Http::withHeaders([
            'Authorization' => 'rZK1aYs5KDpcJ1EZWhDT',
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $nomor,
            'message' => $pesan,
        ]);

        Log::info('Response Fonnte: ', $response->json());

        if ($response->successful()) {
            $responseJson = $response->json();

            if (isset($responseJson['status']) && $responseJson['status'] === true && (!isset($responseJson['process']) || $responseJson['process'] !== 'failed')) {
                $status = 'success';
                $pengirimanStatus = 'terkirim';
            } else {
                $status = 'error';
                $pengirimanStatus = 'gagal';
            }

            return [
                'status' => $status,
                'pendaftaran_id' => $pendaftaranId,
                'pengiriman_status' => $pengirimanStatus
            ];
        }

        return [
            'status' => 'error',
            'pendaftaran_id' => $pendaftaranId,
            'pengiriman_status' => 'gagal'
        ];
    }

    public function kirimPesanDariForm(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftaran,pendaftaran_id',
            'nomor' => 'required',
            'pesan' => 'required',
        ]);

        $response = $this->kirim($request->pendaftaran_id, $request->nomor, $request->pesan);

        $message = ($response['status'] === 'success') ? 'Pesan berhasil dikirim.' : 'Gagal mengirim pesan.';

        return response()->json([
            'status' => $response['status'],
            'message' => $message,
            'pendaftaran_id' => $response['pendaftaran_id'],
            'pengiriman_status' => $response['pengiriman_status']
        ]);
    }
}