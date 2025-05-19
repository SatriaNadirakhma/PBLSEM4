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
                $btn  = '<button onclick="modalAction(\'' . url('/jurusan/' . $j->jurusan_id . '/show_ajax') . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/jurusan/' . $j->jurusan_id . '/edit_ajax') . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/jurusan/' . $j->jurusan_id . '/delete_ajax') . '\')" 
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>';
                return $btn;
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

    public function export_excel()
    {
        $jurusan = JurusanModel::with('kampus')
            ->select('jurusan_kode', 'jurusan_nama', 'kampus_id')
            ->orderBy('jurusan_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Jurusan');
        $sheet->setCellValue('C1', 'Nama Jurusan');
        $sheet->setCellValue('D1', 'Nama Kampus');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $baris = 2;
        foreach ($jurusan as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->jurusan_kode);
            $sheet->setCellValue('C' . $baris, $value->jurusan_nama);
            $sheet->setCellValue('D' . $baris, $value->kampus->kampus_nama ?? '-');
            $baris++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Jurusan');

        $filename = 'Data_Jurusan_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $jurusan = JurusanModel::with('kampus')
            ->select('jurusan_kode', 'jurusan_nama', 'kampus_id')
            ->orderBy('jurusan_kode')
            ->get();

        $pdf = Pdf::loadView('jurusan.export_pdf', ['jurusan' => $jurusan]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render(); 

        return $pdf->stream('Data Jurusan ' . date('Y-m-d H:i:s') . '.pdf');
    }
}