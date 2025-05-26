<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PendaftaranModel;
use Yajra\DataTables\DataTables;

class RiwayatController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Pendaftaran',
            'list' => ['Home', 'Riwayat Pendaftaran'],
        ];

        $page = (object) [
            'title' => 'Daftar riwayat pendaftaran mahasiswa',
        ];

        $activeMenu = 'riwayat';

        return view('riwayat.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $riwayat = PendaftaranModel::with(['mahasiswa', 'jadwal', 'detail'])
            ->whereHas('detail', function ($query) use ($request) {
                $query->whereIn('status', ['diterima', 'ditolak']);

                if ($request->status_filter) {
                    $query->where('status', $request->status_filter);
                }
            });

            /// Tambahkan bagian ini untuk mengaktifkan search:
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');

            $riwayat->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($q2) use ($search) {
                    $q2->where('nim', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('mahasiswa_nama', 'like', "%$search%");
                })
                ->orWhere('tanggal_pendaftaran', 'like', "%$search%")
                ->orWhereHas('detail', function ($q2) use ($search) {
                    $q2->where('status', 'like', "%$search%");
                });
            });
        }

        return DataTables::of($riwayat)
            ->addIndexColumn()
            ->addColumn('nim', function($r) {
                return $r->mahasiswa->nim ?? '';
            })
            ->addColumn('nama', function($r) {
                return $r->mahasiswa->mahasiswa_nama ?? '';
            })
            ->addColumn('nik', function($r) {
                return $r->mahasiswa->nik ?? '';
            })
            ->addColumn('tanggal_daftar', function($r) {
                return date('d-m-Y', strtotime($r->tanggal_pendaftaran));
            })
            ->addColumn('status', function($r) {
                $detail = $r->detail_terakhir;
                return $detail ? ucfirst($detail->status) : '';
            })
            ->addColumn('aksi', function ($r) {
                $url = url('/riwayat/' . $r->pendaftaran_id . '/show_ajax');
                return '<button onclick="modalAction(\'' . $url . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax($id)
    {
        $pendaftaran = PendaftaranModel::with([
            'mahasiswa.prodi.jurusan.kampus',
            'detail',
            'jadwal'
        ])->find($id);

        return view('riwayat.show_ajax', compact('pendaftaran'));
    }
}