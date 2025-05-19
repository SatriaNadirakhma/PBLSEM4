<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Admin',
            'list' => ['Home', 'Admin'],
        ];

        $page = (object) [
            'title' => 'Daftar admin yang terdaftar dalam sistem',
        ];

        $activeMenu = 'admin';

        return view('admin.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $admin = AdminModel::select('admin_id', 'admin_nama', 'no_telp');

        if ($request->has('search_query') && $request->search_query != '') {
            $admin->where('admin_nama', 'like', '%' . $request->search_query . '%');
    }

        return DataTables::of($admin)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($a) {
                $btn  = '<button onclick="modalAction(\'' . url('/prodi/' . $a->admin_id . '/show_ajax') . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $a->admin_id . '/edit_ajax') . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $a->admin_id . '/delete_ajax') . '\')" 
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
        $admin = AdminModel::find($id);
        return view('admin.show_ajax', ['admin' => $admin]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        return view('admin.create_ajax');
    }

    public function store_ajax(Request $request)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'admin_nama' => 'required|string|max:100',
                    'no_telp' => 'required|string|max:20',
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
                    // Menyimpan data admin
                    AdminModel::create($request->all());

                    return response()->json([
                        'status' => true,
                        'message' => 'Data admin berhasil disimpan',
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

        $admin = AdminModel::find($id);
        return view('admin.confirm_ajax', ['admin' => $admin]);
    }

    // Delete ajax
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $admin = AdminModel::find($id);
            if ($admin) {
                $admin->delete();
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
        $admin = AdminModel::find($id);

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data admin tidak ditemukan'
            ]);
        }

        return view('admin.edit_ajax', ['admin' => $admin]);
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
            'admin_nama' => 'required|min:3|max:100',
            'no_telp' => 'required|min:3|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $admin = AdminModel::find($id);

        if (!$admin) {
            return response()->json([
                'status' => false,
                'message' => 'Data admin tidak ditemukan.'
            ]);
        }

        try {
            $admin->admin_nama = $request->admin_nama;
            $admin->no_telp = $request->no_telp;
            $admin->save();

            return response()->json([
                'status' => true,
                'message' => 'Data admin berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data admin.',
                'error' => $e->getMessage()
            ]);
        }
        return redirect('/');
    }

   // Menampilkan form import admin
    public function import()
    {
        return view('admin.import');
    }

    // Import data admin dari file Excel
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_admin' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_admin');
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
                            'admin_nama' => $value['A'],
                            'no_telp' => $value['B'],
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    AdminModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data admin berhasil diimport'
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

    // Export data admin ke Excel
    public function export_excel()
    {
        $admin = AdminModel::select('admin_nama', 'no_telp')
            ->orderBy('admin_nama')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Admin');
        $sheet->setCellValue('C1', 'Nomor Telepon');

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $baris = 2;
        foreach ($admin as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->admin_nama);
            $sheet->setCellValue('C' . $baris, $value->no_telp);
            $baris++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Admin');

        $filename = 'Data_Admin_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    // Export data admin ke PDF
    public function export_pdf()
    {
        $admin = AdminModel::select('admin_nama', 'no_telp')
            ->orderBy('admin_nama')
            ->get();

        $pdf = Pdf::loadView('admin.export_pdf', ['admin' => $admin]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Admin ' . date('Y-m-d H:i:s') . '.pdf');
    }
}

