<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InformasiModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class InformasiController extends Controller
{
    // Menampilkan halaman utama informasi
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Pengumuman',
            'list' => ['Home', 'Pengumuman'],
        ];

        $page = (object) [
            'title' => 'Daftar Pengumuman yang terdaftar dalam sistem',
        ];

        $activeMenu = 'informasi';

        return view('informasi.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Mengambil data untuk DataTables
    public function list(Request $request)
    {
        $informasi = InformasiModel::select('informasi_id', 'judul', 'isi', 'created_at', 'updated_at');

        // Filter berdasarkan pencarian
        if ($request->has('search_query') && !empty($request->search_query)) {
            $informasi->where('judul', 'like', '%' . $request->search_query . '%');
        }

        return DataTables::of($informasi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($k) {
                $btn  = '<button onclick="modalAction(\'' . url('/informasi/' . $k->informasi_id . '/show_ajax') . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/informasi/' . $k->informasi_id . '/edit_ajax') . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/informasi/' . $k->informasi_id . '/delete_ajax') . '\')" 
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan detail informasi via AJAX
    public function show_ajax(string $id)
    {
        $informasi = InformasiModel::find($id);
        return view('informasi.show_ajax', ['informasi' => $informasi]);
    }

    // Menyimpan informasi baru via AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'judul' => 'required|string|max:255',
                'isi' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                InformasiModel::create($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data informasi berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                ]);
            }
        }

        return redirect('/');
    }

    // Menghapus informasi via AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $informasi = InformasiModel::find($id);
            if ($informasi) {
                $informasi->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    // Menampilkan form edit informasi
    public function edit_ajax(string $id)
    {
        $informasi = InformasiModel::find($id);
        if (!$informasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data informasi tidak ditemukan'
            ]);
        }

        return view('informasi.edit_ajax', ['informasi' => $informasi]);
    }

    // Memperbarui informasi via AJAX
    public function update_ajax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $informasi = InformasiModel::find($id);
        if (!$informasi) {
            return response()->json([
                'status' => false,
                'message' => 'Data informasi tidak ditemukan.'
            ]);
        }

        try {
            $informasi->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data informasi berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data.',
                'error' => $e->getMessage()
            ]);
        }
    }
}
