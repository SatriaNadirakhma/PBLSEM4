<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TendikModel;
use App\Models\KampusModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class TendikController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Peserta Tendik',
            'list' => ['Home', 'Biodata', 'Peserta Tendik'],
        ];

        $page = (object) [
            'title' => 'Daftar tenaga kependidikan (Tendik)',
        ];

        $activeMenu = 'peserta-tendik';

        $kampus = KampusModel::all();

        return view('biodata.tendik.index', compact('breadcrumb', 'page', 'activeMenu', 'kampus'));
    }

    public function list(Request $request)
    {
    $tendik = TendikModel::select('tendik_id', 'nip', 'nik', 'tendik_nama', 'no_telp', 'alamat_asal', 'alamat_sekarang', 'jenis_kelamin', 'kampus_id')
        ->with('kampus'); // Pastikan relasi kampus sudah dimuat

    // Filter berdasarkan nama tendik
        if ($request->has('search_query') && $request->search_query != '') {
            $tendik->where('tendik_nama', 'like', '%' . $request->search_query . '%');
        }

    // Filter berdasarkan nama kampus
    if ($request->has('kampus_nama') && $request->kampus_nama != '') {
        $tendik->whereHas('kampus', function ($query) use ($request) {
            $query->where('kampus_nama', 'like', '%' . $request->kampus_nama . '%');
        });
    }

        return DataTables::of($tendik) 
        ->addIndexColumn()
        ->addColumn('kampus_nama', function ($t) {
            return $t->kampus ? $t->kampus->kampus_nama : '-'; // Pastikan kampus_nama terisi dengan benar
        })
        ->addColumn('aksi', function ($t) {
            $btn = '<button onclick="modalAction(\'' . route('biodata.tendik.show_ajax', $t->tendik_id) . '\')" class="btn btn-info btn-sm me-1">Detail</button>';
            $btn .= '<button onclick="modalAction(\'' . route('biodata.tendik.edit_ajax', $t->tendik_id) . '\')" class="btn btn-warning btn-sm me-1">Edit</button>';
            $btn .= '<button onclick="modalAction(\'' . route('biodata.tendik.confirm_ajax', $t->tendik_id) . '\')" class="btn btn-danger btn-sm">Hapus</button>';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);

    }

    public function show_ajax(string $id)
    {
        $tendik = TendikModel::find($id);
        return view('biodata.tendik.show_ajax', ['tendik' => $tendik]);
    }

    public function create_ajax()
    {
        return view('biodata.tendik.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nip' => 'required|string|max:20|unique:tendik,nip',
                'nik' => 'required|string|max:20',
                'tendik_nama' => 'required|string|max:100',
                'no_telp' => 'nullable|string',
                'alamat_asal' => 'nullable|string',
                'alamat_sekarang' => 'nullable|string',
                'jenis_kelamin' => 'required|string',
                'kampus_id' => 'required|integer',
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
                TendikModel::create($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data tendik berhasil disimpan',
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

    public function confirm_ajax(string $id)
    {
        $tendik = TendikModel::find($id);
        return view('biodata.tendik.confirm_ajax', ['tendik' => $tendik]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $tendik = TendikModel::find($id);
            if ($tendik) {
                $tendik->delete();
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

    public function edit_ajax(string $id)
    {
        $tendik = TendikModel::find($id);

        if (!$tendik) {
            return response()->json([
                'status' => false,
                'message' => 'Data tendik tidak ditemukan'
            ]);
        }

        return view('biodata.tendik.edit_ajax', ['tendik' => $tendik]);
    }

    public function update_ajax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nip' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'tendik_nama' => 'required|string|max:100',
            'no_telp' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'alamat_sekarang' => 'nullable|string',
            'jenis_kelamin' => 'required|string',
            'kampus_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $tendik = TendikModel::find($id);

        if (!$tendik) {
            return response()->json([
                'status' => false,
                'message' => 'Data tendik tidak ditemukan.'
            ]);
        }

        try {
            $tendik->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data tendik berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data tendik.',
                'error' => $e->getMessage()
            ]);
        }
        return redirect('/');
    }
}
