<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KampusModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class KampusController extends Controller
{
    // Menampilkan halaman utama kampus
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kampus',
            'list' => ['Home', 'Kampus'],
        ];

        $page = (object) [
            'title' => 'Daftar kampus yang terdaftar dalam sistem',
        ];

        $activeMenu = 'kampus';

        return view('kampus.index', compact('breadcrumb', 'page', 'activeMenu'));
    }


    // Mengambil data kampus untuk DataTables
    public function list(Request $request)
    {
        $kampus = KampusModel::select('kampus_id', 'kampus_kode', 'kampus_nama');

       if ($request->has('search_query') && $request->search_query != '') {
        $kampus->where('kampus_nama', 'like', '%' . $request->search_query . '%');
    }

        return DataTables::of($kampus)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($k) {
                // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/kampus/' . $k->kampus_id . '/show_ajax') . '\')" class="btn btn-info btn-sm me-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/kampus/' . $k->kampus_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm me-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/kampus/' . $k->kampus_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

     //Show AJAX
    public function show_ajax(string $id)
    {
        $kampus = KampusModel::find($id);
        return view('kampus.show_ajax', ['kampus' => $kampus]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        return view('kampus.create_ajax');
    }

    // Store ajax
        public function store_ajax(Request $request)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kampus_kode' => 'required|string|max:20|unique:kampus,kampus_kode',
                    'kampus_nama' => 'required|string|max:100',
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
                    // Menyimpan data kampus
                    KampusModel::create($request->all());

                    return response()->json([
                        'status' => true,
                        'message' => 'Data kampus berhasil disimpan',
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
    public function confirm_ajax(string $id){

        $kampus = KampusModel::find($id);
        return view('kampus.confirm_ajax', ['kampus' => $kampus]);
    }

    // Delete ajax
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kampus = KampusModel::find($id);
            if ($kampus) {
                $kampus->delete();
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

    //Edit AJAX
    public function edit_ajax(string $id)
    {
        $kampus = KampusModel::find($id);

        if (!$kampus) {
            return response()->json([
                'status' => false,
                'message' => 'Data kampus tidak ditemukan'
            ]);
        }

        return view('kampus.edit_ajax', ['kampus' => $kampus]);
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
            'kampus_kode' => 'required|min:3|max:20',
            'kampus_nama' => 'required|min:3|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $kampus = KampusModel::find($id);

        if (!$kampus) {
            return response()->json([
                'status' => false,
                'message' => 'Data kampus tidak ditemukan.'
            ]);
        }

        try {
            $kampus->kampus_kode = $request->kampus_kode;
            $kampus->kampus_nama = $request->kampus_nama;
            $kampus->save();

            return response()->json([
                'status' => true,
                'message' => 'Data kampus berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data kampus.',
                'error' => $e->getMessage()
            ]);
        }
        return redirect('/');
    }

   // Menampilkan form import kampus
    public function import()
    {
        return view('kampus.import');
    }

    // Import data kampus dari file Excel
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kampus' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_kampus');
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
                            'kampus_kode' => $value['A'],
                            'kampus_nama' => $value['B'],
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    KampusModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data kampus berhasil diimport'
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

    // Export data kampus ke Excel
    public function export_excel()
    {
        $kampus = KampusModel::select('kampus_kode', 'kampus_nama')
            ->orderBy('kampus_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Kampus');
        $sheet->setCellValue('C1', 'Nama Kampus');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $baris = 2;
        foreach ($kampus as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->kampus_kode);
            $sheet->setCellValue('C' . $baris, $value->kampus_nama);
            $baris++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Kampus');

        $filename = 'Data_Kampus_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    // Export data kampus ke PDF
    public function export_pdf()
    {
        $kampus = KampusModel::select('kampus_kode', 'kampus_nama')
            ->orderBy('kampus_kode')
            ->get();

        $pdf = Pdf::loadView('kampus.export_pdf', ['kampus' => $kampus]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Kampus ' . date('Y-m-d H:i:s') . '.pdf');
    }
}