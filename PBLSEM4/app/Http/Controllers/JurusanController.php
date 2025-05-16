<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurusanModel;
use App\Models\KampusModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class JurusanController extends Controller
{
    // Menampilkan halaman utama jurusan
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jurusan',
            'list' => ['Home', 'Jurusan'],
        ];

        $page = (object) [
            'title' => 'Daftar jurusan yang terdaftar dalam sistem',
        ];

        $activeMenu = 'jurusan';

        // Ambil list kampus untuk filter dropdown
        $kampusList = KampusModel::orderBy('kampus_nama')->get();

        return view('jurusan.index', compact('breadcrumb', 'page', 'activeMenu', 'kampusList'));
    }

    public function list(Request $request)
    {
        $query = JurusanModel::with('kampus')->select('jurusan.*');

        // Filter berdasarkan nama jurusan (opsional)
        if ($request->has('search_query') && $request->search_query != '') {
            $query->where('jurusan_nama', 'like', '%' . $request->search_query . '%');
        }

        // Filter berdasarkan kampus_id (jika diberikan dari dropdown)
        if ($request->has('filter_kampus') && $request->filter_kampus != '') {
            $query->where('kampus_id', $request->filter_kampus);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kampus_nama', function ($j) {
                return $j->kampus->kampus_nama ?? '-';
            })
            ->addColumn('aksi', function ($j) {
                return '
                    <button onclick="modalAction(\'' . url('/jurusan/' . $j->jurusan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm me-1">Detail</button>
                    <button onclick="modalAction(\'' . url('/jurusan/' . $j->jurusan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm me-1">Edit</button>
                    <button onclick="modalAction(\'' . url('/jurusan/' . $j->jurusan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


        // Show AJAX
        public function show_ajax(string $id)
        {
            $jurusan = JurusanModel::find($id);
            return view('jurusan.show_ajax', ['jurusan' => $jurusan]);
        }

       public function create_ajax()
    {
        $kampus = KampusModel::orderBy('kampus_nama')->get();
        return view('jurusan.create_ajax', compact('kampus'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $validator = Validator::make($request->all(), [
                'jurusan_kode' => 'required|string|max:20|unique:jurusan,jurusan_kode',
                'jurusan_nama' => 'required|string|max:100',
                'kampus_id' => 'required|exists:kampus,kampus_id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            try {
                JurusanModel::create($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data jurusan berhasil disimpan',
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

        // Confirm ajax
        public function confirm_ajax(string $id)
        {
            $jurusan = JurusanModel::find($id);
            return view('jurusan.confirm_ajax', ['jurusan' => $jurusan]);
        }

        // Delete ajax
        public function delete_ajax(Request $request, $id)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $jurusan = JurusanModel::find($id);
                if ($jurusan) {
                    $jurusan->delete();
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

    // Edit AJAX
    public function edit_ajax(Request $request, $id)
    {
        // Cek apakah request adalah AJAX
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }

        $jurusan = JurusanModel::find($id);
        $kampus = KampusModel::select('kampus_id', 'kampus_nama')->get();

        return view('jurusan.edit_ajax', [
            'jurusan' => $jurusan,
            'kampus' => $kampus
        ]);
    }



    // Update AJAX
    public function update_ajax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'jurusan_kode' => 'required|min:3|max:20',
            'jurusan_nama' => 'required|min:3|max:100',
            'kampus_id' => 'required|exists:kampus,kampus_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $jurusan = JurusanModel::find($id);

        if (!$jurusan) {
            return response()->json([
                'status' => false,
                'message' => 'Data jurusan tidak ditemukan.'
            ]);
        }

        try {
            $jurusan->jurusan_kode = $request->jurusan_kode;
            $jurusan->jurusan_nama = $request->jurusan_nama;
            $jurusan->kampus_id = $request->kampus_id;
            $jurusan->save();

            return response()->json([
                'status' => true,
                'message' => 'Data jurusan berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data jurusan.',
                'error' => $e->getMessage()
            ]);
        }
    }

    // Import form jurusan
    public function import()
    {
        return view('jurusan.import');
    }

    // Import data jurusan dari Excel
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_jurusan' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_jurusan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // header row
                        $insert[] = [
                            'jurusan_kode' => $value['A'],
                            'jurusan_nama' => $value['B'],
                            'kampus_id' => $value['C'],  // pastikan kode kampus/id di kolom C
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    JurusanModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data jurusan berhasil diimport'
                    ]);
                }
            }

            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }

        return redirect('/');
    }
}