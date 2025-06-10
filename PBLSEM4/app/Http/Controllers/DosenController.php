<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DosenModel;
use App\Models\JurusanModel;

use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class DosenController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Peserta Dosen',
            'list' => ['Home', 'Biodata', 'Peserta Dosen'],
        ];

        $page = (object) ['title' => 'Daftar Dosen'];
        $activeMenu = 'peserta-dosen';
        $jurusan = JurusanModel::all();

        return view('biodata.dosen.index', compact('breadcrumb', 'page', 'activeMenu', 'jurusan'));
    }

    public function list(Request $request)
    {
        $dosen = DosenModel::with('jurusan')->select(
            'dosen_id', 'nidn', 'nik', 'dosen_nama',
            'jenis_kelamin', 'jurusan_id'
        );

        if ($request->has('search_query') && $request->search_query != '') {
            $dosen->where('dosen_nama', 'like', '%' . $request->search_query . '%');
        }

        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $dosen->where('jurusan_id', $request->jurusan_id);
        }

        return DataTables::of($dosen)
            ->addIndexColumn()
            ->addColumn('jurusan_id', function ($t) {
                return $t->jurusan->jurusan_nama ?? '-';
            })
            ->addColumn('aksi', function ($t) {
                $btn = '<button onclick="modalAction(\'' . route('biodata.dosen.show_ajax', $t->dosen_id) . '\')"  
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';
                $btn .= '<button onclick="modalAction(\'' . route('biodata.dosen.edit_ajax', $t->dosen_id) . '\')"  
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';
                $btn .= '<button onclick="modalAction(\'' . route('biodata.dosen.confirm_ajax', $t->dosen_id) . '\')"  
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax(string $id)
    {
        $dosen = DosenModel::find($id);
        return view('biodata.dosen.show_ajax', compact('dosen'));
    }

    public function create_ajax()
    {
        // Ambil semua jurusan dan mapping ke id dan jurusan_nama
        $jurusan = JurusanModel::select('jurusan_id as id', 'jurusan_nama')->get();
        return view('biodata.dosen.create_ajax', compact('jurusan'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nidn' => 'required|string|max:20|unique:dosen,nidn',
                'nik' => 'required|string|max:20',
                'dosen_nama' => 'required|string|max:100',
                'no_telp' => 'nullable|string',
                'alamat_asal' => 'nullable|string',
                'alamat_sekarang' => 'nullable|string',
                'jenis_kelamin' => 'required|string',
                'jurusan_id' => 'required|integer',
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
                DosenModel::create($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data dosen berhasil disimpan',
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

    public function edit_ajax(string $id)
    {
        $dosen = DosenModel::find($id);
        $jurusan = JurusanModel::all();

        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan'
            ]);
        }

        return view('biodata.dosen.edit_ajax', compact('dosen', 'jurusan'));
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
            'nidn' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'dosen_nama' => 'required|string|max:100',
            'no_telp' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'alamat_sekarang' => 'nullable|string',
            'jenis_kelamin' => 'required|string',
            'jurusan_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $dosen = DosenModel::find($id);

        if (!$dosen) {
            return response()->json([
                'status' => false,
                'message' => 'Data dosen tidak ditemukan.'
            ]);
        }

        try {
            $dosen->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data dosen berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data dosen.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $dosen = DosenModel::find($id);
        $jurusan = JurusanModel::all();
        if ($dosen && $dosen->jurusan_id) {
            $jurusan_nama = JurusanModel::where('jurusan_id', $dosen->jurusan_id)->value('jurusan_nama');
            $dosen->jurusan_nama = $jurusan_nama;
        }
        return view('biodata.dosen.confirm_ajax', compact('dosen'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $dosen = DosenModel::find($id);
            if ($dosen) {
                $dosen->delete();
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

    public function import()
    {
        return view('biodata.dosen.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'file_dosen' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_dosen');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            foreach ($data as $rowNumber => $row) {
                if ($rowNumber > 1) {
                    $insert[] = [
                        'nidn' => $row['A'],
                        'nik' => $row['B'],
                        'dosen_nama' => $row['C'],
                        'no_telp' => $row['D'],
                        'alamat_asal' => $row['E'],
                        'alamat_sekarang' => $row['F'],
                        'jenis_kelamin' => $row['G'],
                        'jurusan_id' => $row['H'],
                        'created_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                DosenModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data dosen berhasil diimport'
                ]);
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
        $dosen = DosenModel::select('nidn', 'nik', 'dosen_nama', 'no_telp', 'alamat_asal', 'alamat_sekarang', 'jenis_kelamin')
            ->orderBy('dosen_nama')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIDN');
        $sheet->setCellValue('C1', 'NIK');
        $sheet->setCellValue('D1', 'Nama Dosen');
        $sheet->setCellValue('E1', 'No Telepon');
        $sheet->setCellValue('F1', 'Alamat Asal');
        $sheet->setCellValue('G1', 'Alamat Sekarang');
        $sheet->setCellValue('H1', 'Jenis Kelamin');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($dosen as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->nidn);
            $sheet->setCellValue('C' . $baris, $value->nik);
            $sheet->setCellValue('D' . $baris, $value->dosen_nama);
            $sheet->setCellValue('E' . $baris, $value->no_telp);
            $sheet->setCellValue('F' . $baris, $value->alamat_asal);
            $sheet->setCellValue('G' . $baris, $value->alamat_sekarang);
            $sheet->setCellValue('H' . $baris, $value->jenis_kelamin);
            $baris++;
        }

        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Dosen');
        $filename = 'Data_Dosen_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $dosen = DosenModel::with('jurusan')->orderBy('dosen_nama')->get();

        $pdf = Pdf::loadView('biodata.dosen.export_pdf', ['dosen' => $dosen]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Dosen ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
