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
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
        $informasi = InformasiModel::select('informasi_id', 'judul', 'isi');

       if ($request->has('search_query') && $request->search_query != '') {
        $informasi->where('judul', 'like', '%' . $request->search_query . '%');
    }

        return DataTables::of($informasi)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($i) {
                $btn  = '<button onclick="modalAction(\'' . url('/informasi/' . $i->informasi_id . '/show_ajax') . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/informasi/' . $i->informasi_id . '/edit_ajax') . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/informasi/' . $i->informasi_id . '/delete_ajax') . '\')" 
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>';
                return $btn;
            })
            ->rawColumns(['aksi', 'isi']) // Memberitahu bahwa kolom aksi dan isi adalah HTML
            ->make(true);
    }

     //Show AJAX
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
                'isi' => 'required|string|max:5000',
                'create_at' => now(),
                'updated_at' => now(),
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

    public function create_ajax()
    {
        $jurusan = InformasiModel::all();
        return view('informasi.create_ajax');
    }

    // confirm ajax
    public function confirm_ajax(string $id){

        $informasi = InformasiModel::find($id);
        return view('informasi.confirm_ajax', ['informasi' => $informasi]);
    }

    // Delete ajax
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

    //Edit AJAX
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
            'judul' => 'required|min:3|max:255',
            'isi' => 'required|min:3|max:5000',
            'updated_at' => now(),
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
            $informasi->judul = $request->judul;
            $informasi->isi = $request->isi;
            $informasi->save();

            return response()->json([
                'status' => true,
                'message' => 'Data informasi berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data informasi.',
                'error' => $e->getMessage()
            ]);
        }
        return redirect('/');
    }

    // Menampilkan form import informasi
    public function import()
    {
        return view('informasi.import');
    }

    // Import data informasi dari file Excel
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_informasi' => ['required', 'mimes:xlsx', 'max:2025']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            
            $file = $request->file('file_informasi');

            if (!$file) {
                return response()->json([
                    'status' => false,
                    'message' => 'File tidak ditemukan!'
                ]);
            }

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
                            'judul'       => $value['A'] ?? null,
                            'isi'         => $value['B'] ?? null,
                            'updated_at' => now(),
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert) > 0) { 
                    InformasiModel::insertOrIgnore($insert); 
                    return response()->json([
                        'status' => true,
                        'message' => 'Data informasi berhasil diimport'
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


    // Export data informasi ke Excel
    public function export_excel()
    {
        $informasi = InformasiModel::select('judul', 'isi')
            ->orderBy('informasi_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Judul');
        $sheet->setCellValue('C1', 'Isi');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $baris = 2;
        foreach ($informasi as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->judul);
            $sheet->setCellValue('C' . $baris, $value->isi);
            $baris++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data informasi');

        $filename = 'Data_informasi_'.'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    // Export data informasi ke PDF
    public function export_pdf()
    {
        $informasi = InformasiModel::select( 'judul', 'isi')
            ->orderBy('informasi_id') 
            ->get();

        $pdf = Pdf::loadView('informasi.export_pdf', ['informasi' => $informasi]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data informasi ' . '.pdf');
    }

    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Judul');
        $sheet->setCellValue('B1', 'Isi');

        // Contoh baris
        $sheet->setCellValue('A2', 'Pendaftaran TOEIC');
        $sheet->setCellValue('B2', 'Pendaftaran TOEIC akan dibuka mulai tanggal 1 Juni 2025 hingga 31 Juni 2025.');
       

        $filename = 'file_informasi.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;

    }
} 