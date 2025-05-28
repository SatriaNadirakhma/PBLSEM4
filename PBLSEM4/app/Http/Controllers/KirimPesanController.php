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
                    <button onclick="modalAction(\'' . url('kirimpesan/' . $r->pendaftaran_id . '/form') . '\')" 
                        class="btn btn-success btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                        <i class="fab fa-whatsapp me-1"></i> Kirim Pesan
                    </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function form($id)
    {
        $data = PendaftaranModel::with('mahasiswa')->findOrFail($id);
        return view('kirimpesan.form', compact('data'));
    }

    public function kirim(Request $request)
    {
        $request->validate([
            'nomor' => 'required',
            'pesan' => 'required',
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'rZK1aYs5KDpcJ1EZWhDT',
        ])->asForm()->post('https://api.fonnte.com/send', [
            'target' => $request->nomor,
            'message' => $request->pesan,
        ]);

        Log::info('Response Fonnte: ', $response->json());

        if ($response->successful()) {
        $responseJson = $response->json();

        if (isset($responseJson['status']) && $responseJson['status'] === true) {
            $processStatus = $responseJson['process'] ?? 'failed';

            // Anggap success kalau process status 'success' atau 'pending'
            if ($processStatus === 'success' || $processStatus === 'pending') {
                $message = 'Pesan berhasil dikirim dan sedang dalam antrean.';
                $status = 'success';
            } else {
                $message = 'Gagal mengirim pesan. Status: ' . $processStatus;
                $status = 'error';
            }

            return response()->json([
                'status' => $status,
                'message' => $message
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Gagal mengirim pesan.',
        ]);
        }

    }

}
