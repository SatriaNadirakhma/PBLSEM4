<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use Illuminate\Http\Request;
use App\Models\JurusanModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdiController extends Controller
{
    
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Program Studi',
            'list' => ['Home', 'Program Studi'],
        ];

        $page = (object) [
            'title' => 'Daftar program studi yang terdaftar dalam sistem',
        ];

        $activeMenu = 'prodi';

        $jurusan = JurusanModel::orderBy('jurusan_nama')->get();

        return view('prodi.index', compact('breadcrumb', 'page', 'activeMenu', 'jurusan'));
    }

    public function list(Request $request)
    {
        $prodi = ProdiModel::with('jurusan')
            ->select('prodi_id', 'prodi_kode', 'prodi_nama', 'jurusan_id')
            ->orderBy('prodi_nama');

       if ($request->has('search_query') && $request->search_query != '') {
        $prodi->where('prodi_nama', 'like', '%' . $request->search_query . '%');
        }

        if ($request->has('jurusan_nama') && $request->jurusan_nama != '') {
            $prodi->whereHas('jurusan', function ($query) use ($request) {
            $query->where('jurusan_nama', 'like', '%' . $request->jurusan_nama . '%');
        });
        }

        if ($request->has('filter_jurusan') && is_numeric($request->filter_jurusan) && $request->filter_jurusan != '') {
            $prodi->where('jurusan_id', intval($request->filter_jurusan));
        }

        return DataTables::of($prodi)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('jurusan_nama', function ($p) {
                return $p->jurusan ? $p->jurusan->jurusan_nama : '-'; // Pastikan prodi_nama terisi dengan benar
            })
            ->addColumn('aksi', function ($k) {
                // Menambahkan kolom aksi
                $btn  = '<button onclick="modalAction(\'' . url('/prodi/' . $k->prodi_id . '/show_ajax') . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $k->prodi_id . '/edit_ajax') . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $k->prodi_id . '/delete_ajax') . '\')" 
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

    public function show_ajax(string $id)
    {
        $prodi = ProdiModel::find($id);
        
        return view('prodi.show_ajax', [
            'prodi' => $prodi,
            'jurusan' => JurusanModel::find($prodi->jurusan_id)
        ]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        $jurusan = JurusanModel::all();
        return view('prodi.create_ajax', ['jurusan' => $jurusan]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi_kode' => 'required|string|max:20|unique:prodi,prodi_kode',
                'prodi_nama' => 'required|string|max:100',
                'jurusan_id' => 'required|exists:jurusan,jurusan_id',
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
                // Menyimpan data prodi
                ProdiModel::create($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data prodi berhasil disimpan',
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
        $prodi = ProdiModel::find($id);
        $jurusan = JurusanModel::select('jurusan_id', 'jurusan_nama')->get();
        
        return view('prodi.edit_ajax', ['prodi' => $prodi, 'jurusan' => $jurusan]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi_kode' => 'required|string|max:20|unique:prodi,prodi_kode,' . $id . ',prodi_id',
                'prodi_nama' => 'required|string|max:100',
                'jurusan_id' => 'required|exists:jurusan,jurusan_id',
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
                // Menyimpan data prodi
                ProdiModel::find($id)->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data prodi berhasil disimpan',
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
        $prodi = ProdiModel::find($id);
        return view('prodi.confirm_ajax', ['prodi' => $prodi]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $prodi = ProdiModel::find($id);
        if ($prodi) {
            $prodi->delete();
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
        return view('prodi.import');
    }

    // Import data prodi dari file Excel
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_prodi' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_prodi');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // baris ke-1 adalah header
                        $insert[] = [
                            'prodi_kode' => $value['A'],
                            'prodi_nama' => $value['B'],
                            'jurusan_id' => $value['C'],
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    ProdiModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data prodi berhasil diimport'
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

    // Export data prodi ke Excel
    public function export_excel()
    {
        $prodi = ProdiModel::select('prodi_kode', 'prodi_nama')
            ->orderBy('prodi_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Prodi');
        $sheet->setCellValue('C1', 'Nama Prodi');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $baris = 2;
        foreach ($prodi as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->prodi_kode);
            $sheet->setCellValue('C' . $baris, $value->prodi_nama);
            $baris++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Prodi');

        $filename = 'Data_Prodi_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    // Export data prodi ke PDF
    public function export_pdf()
    {
        $prodi = ProdiModel::select('prodi_kode', 'prodi_nama', 'jurusan_id')
            ->with('jurusan')
            ->orderBy('prodi_kode')
            ->get();

        $pdf = Pdf::loadView('prodi.export_pdf', ['prodi' => $prodi]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Prodi ' . date('Y-m-d H:i:s') . '.pdf');
    }

}
