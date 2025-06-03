<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class JadwalController extends Controller
{

    // Menampilkan halaman utama Jadwal
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Jadwal Tes TOEIC',
            'list' => ['Home', 'jadwal'],
        ];

        $page = (object) [
            'title' => 'Daftar jadwal yang terdaftar dalam sistem',
        ];

        $activeMenu = 'jadwal';

        return view('jadwal.index', compact('breadcrumb', 'page', 'activeMenu'));
    }


    // Mengambil data Jadwal untuk DataTables
    public function list(Request $request)
    {
        $jadwal = JadwalModel::select('jadwal_id', 'tanggal_pelaksanaan','jam_mulai','keterangan');

       if ($request->has('search_query') && $request->search_query != '') {
        $jadwal->where('keterangan', 'like', '%' . $request->search_query . '%');
    }

        return DataTables::of($jadwal)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($k) {
                $btn  = '<button onclick="modalAction(\'' . url('/jadwal/' . $k->jadwal_id . '/show_ajax') . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/jadwal/' . $k->jadwal_id . '/edit_ajax') . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/jadwal/' . $k->jadwal_id . '/delete_ajax') . '\')" 
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

     //Show AJAX
    public function show_ajax(string $id)
    {
        $jadwal = JadwalModel::find($id);
        return view('jadwal.show_ajax', ['jadwal' => $jadwal]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        return view('jadwal.create_ajax');
    }

    // Store ajax
        public function store_ajax(Request $request)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'tanggal_pelaksanaan' => 'required|date',
                    'jam_mulai' => 'required|date_format:H:i',
                    'keterangan' => 'required|string|max:100',
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
                    // Menyimpan data jadwal
                    JadwalModel::create($request->all());

                    return response()->json([
                        'status' => true,
                        'message' => 'Data jadwal berhasil disimpan',
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

        $jadwal = JadwalModel::find($id);
        return view('jadwal.confirm_ajax', ['jadwal' => $jadwal]);
    }

    // Delete ajax
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $jadwal = JadwalModel::find($id);
            if ($jadwal) {
                $jadwal->delete();
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
        $jadwal = JadwalModel::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => false,
                'message' => 'Data jadwal tidak ditemukan'
            ]);
        }

        return view('jadwal.edit_ajax', ['jadwal' => $jadwal]);
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
            'tanggal_pelaksanaan' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i:s',
            'keterangan' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $jadwal = JadwalModel::find($id);

        if (!$jadwal) {
            return response()->json([
                'status' => false,
                'message' => 'Data jadwal tidak ditemukan.'
            ]);
        }

        try {
            $jadwal->tanggal_pelaksanaan = $request->input('tanggal_pelaksanaan');
            $jadwal->jam_mulai = $request->input('jam_mulai');
            $jadwal->keterangan = $request->input('keterangan');
            // Simpan perubahan
            $jadwal->updated_at = now(); // Update timestamp
            $jadwal->save();

            return response()->json([
                'status' => true,
                'message' => 'Data jadwal berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data Jadwal.',
                'error' => $e->getMessage()
            ]);
        }
        return redirect('/');
    }

   // Menampilkan form import jadwal
    public function import()
    {
        return view('jadwal.import');
    }

    // Import data jadwal dari file Excel
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_jadwal' => ['required', 'mimes:xlsx', 'max:2025']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_jadwal');

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
                        // Tanggal
                        if (is_numeric($value['A'])) {
                            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['A'])->format('Y-m-d');
                        } else {
                            $tanggal = \Carbon\Carbon::parse($value['A'])->format('Y-m-d');
                        }

                        // Jam mulai
                        if (is_numeric($value['B'])) {
                            $jam_mulai = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value['B'])->format('H:i');
                        } else {
                            $jam_mulai = \Carbon\Carbon::parse($value['B'])->format('H:i');
                        }

                        $insert[] = [
                            'tanggal_pelaksanaan' => $tanggal,
                            'jam_mulai' => $jam_mulai,
                            'keterangan' => $value['C'],
                            'updated_at' => now(),
                            'created_at'  => now(),
                        ];
                    }
                }

                if (count($insert) > 0) { 
                    JadwalModel::insertOrIgnore($insert); 
                    return response()->json([
                        'status' => true,
                        'message' => 'Data jadwal berhasil diimport'
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


    // Export data Jadwal ke Excel
    public function export_excel()
    {
        $jadwal = JadwalModel::select('tanggal_pelaksanaan', 'jam_mulai', 'keterangan')
            ->orderBy('jadwal_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal Pelaksanaan');
        $sheet->setCellValue('C1', 'Jam Mulai');
        $sheet->setCellValue('D1', 'Keterangan');
        $sheet->getStyle('A1:C1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $baris = 2;
        foreach ($jadwal as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->tanggal_pelaksanaan);
            $sheet->setCellValue('C' . $baris, $value->jam_mulai);
            $sheet->setCellValue('D' . $baris, $value->keterangan);
            $sheet->getStyle('B' . $baris)->getNumberFormat()->setFormatCode('yyyy-mm-dd');
            $sheet->getStyle('C' . $baris)->getNumberFormat()->setFormatCode('hh:mm');
            $baris++;
        }

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Jadwal');

        $filename = 'Data_Jadwal_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    // Export data Jadwal ke PDF
    public function export_pdf()
    {
        $jadwal = JadwalModel::select('tanggal_pelaksanaan', 'jam_mulai', 'keterangan')
            ->orderBy('tanggal_pelaksanaan')
            ->get();

        $pdf = Pdf::loadView('jadwal.export_pdf', ['jadwal' => $jadwal]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Jadwal ' . date('Y-m-d H:i:s') . '.pdf');
    }


    public function download_template()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Tanggal Pelaksanaan');
        $sheet->setCellValue('B1', 'Jam Mulai');
        $sheet->setCellValue('C1', 'Keterangan');

        // Contoh baris
        $sheet->setCellValue('A2', '2025-06-02');
        $sheet->setCellValue('B2', '09:00');
        $sheet->setCellValue('C2', 'TOEIC Batch 1');

        $filename = 'template_Jadwal.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}