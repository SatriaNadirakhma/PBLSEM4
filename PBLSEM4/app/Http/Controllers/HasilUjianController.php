<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilUjianModel;
use App\Models\JadwalModel;
use App\Models\UserModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class HasilUjianController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Hasil Ujian',
            'list' => ['Home', 'Hasil Ujian'],
        ];

        $page = (object) [
            'title' => 'Daftar hasil ujian dalam sistem',
        ];

        $activeMenu = 'hasil_ujian';

        return view('hasil_ujian.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $data = HasilUjianModel::with(['jadwal', 'user']);

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('jadwal', fn($row) => $row->jadwal->nama ?? '-')
            ->addColumn('user', fn($row) => $row->user->name ?? '-')
            ->addColumn('aksi', function ($row) {
                $id = $row->hasil_id;
                return '
                    <button onclick="modalAction(\'' . url("/hasil_ujian/$id/show_ajax") . '\')" class="btn btn-info btn-sm">Detail</button>
                    <button onclick="modalAction(\'' . url("/hasil_ujian/$id/edit_ajax") . '\')" class="btn btn-warning btn-sm">Edit</button>
                    <button onclick="modalAction(\'' . url("/hasil_ujian/$id/delete_ajax") . '\')" class="btn btn-danger btn-sm">Hapus</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $jadwal = JadwalModel::all();
        $user = UserModel::all();
        return view('hasil_ujian.create_ajax', compact('jadwal', 'user'));
    }

    public function store_ajax(Request $request)
    {
        HasilUjianModel::create([
            'nilai_listening' => $request->nilai_listening,
            'nilai_reading' => $request->nilai_reading,
            'nilai_total' => $request->nilai_total,
            'status_lulus' => $request->status_lulus,
            'catatan' => $request->catatan,
            'jadwal_id' => $request->jadwal_id,
            'user_id' => $request->user_id,
        ]);


        $validator = Validator::make($request->all(), $request->rules());
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            HasilUjianModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    public function show_ajax($id)
    {
        $hasil = HasilUjianModel::with(['jadwal', 'user'])->findOrFail($id);
        return view('hasil_ujian.show_ajax', compact('hasil'));
    }

    public function edit_ajax($id)
    {
        $hasil = HasilUjianModel::findOrFail($id);
        $jadwal = JadwalModel::all();
        $user = UserModel::all();
        return view('hasil_ujian.edit_ajax', compact('hasil', 'jadwal', 'user'));
    }

    public function update_ajax(Request $request, $id)
    {
        $rules = [
            'nilai_listening' => 'required|numeric|min:0|max:495',
            'nilai_reading'   => 'required|numeric|min:0|max:495',
            'nilai_total'     => 'required|numeric|min:0|max:990',
            'status_lulus'    => 'required|in:lulus,tidak_lulus',
            'jadwal_id'       => 'required|exists:jadwal,jadwal_id',
            'user_id'         => 'required|exists:users,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $hasil = HasilUjianModel::findOrFail($id);
        $hasil->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    }

    public function confirm_ajax($id)
    {
        $hasil = HasilUjianModel::findOrFail($id);
        return view('hasil_ujian.confirm_ajax', compact('hasil'));
    }

    public function delete_ajax(Request $request, $id)
    {
        $hasil = HasilUjianModel::find($id);
        if ($hasil) {
            $hasil->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    public function import()
    {
        return view('hasil_ujian.import');
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_hasil_ujian' => 'required|mimes:xlsx|max:1024'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'Validasi gagal', 'msgField' => $validator->errors()]);
        }

        $file = $request->file('file_hasil_ujian');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $data = [];
        foreach ($sheet as $index => $row) {
            if ($index <= 1) continue; // skip header
            $data[] = [
                'nilai_listening' => $row['A'],
                'nilai_reading'   => $row['B'],
                'nilai_total'     => $row['C'],
                'status_lulus'    => $row['D'],
                'jadwal_id'       => $row['E'],
                'user_id'         => $row['F'],
                'created_at'      => now()
            ];
        }

        if (count($data)) {
            HasilUjianModel::insertOrIgnore($data);
            return response()->json(['status' => true, 'message' => 'Data berhasil diimport']);
        }

        return response()->json(['status' => false, 'message' => 'Tidak ada data untuk diimport']);
    }

    public function export_excel()
    {
        $hasil = HasilUjianModel::with(['jadwal', 'user'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Listening');
        $sheet->setCellValue('C1', 'Reading');
        $sheet->setCellValue('D1', 'Total');
        $sheet->setCellValue('E1', 'Status');
        $sheet->setCellValue('F1', 'Jadwal');
        $sheet->setCellValue('G1', 'User');

        $row = 2;
        $no = 1;
        foreach ($hasil as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->nilai_listening);
            $sheet->setCellValue('C' . $row, $item->nilai_reading);
            $sheet->setCellValue('D' . $row, $item->nilai_total);
            $sheet->setCellValue('E' . $row, $item->status_lulus);
            $sheet->setCellValue('F' . $row, $item->jadwal->nama ?? '-');
            $sheet->setCellValue('G' . $row, $item->user->name ?? '-');
            $row++;
        }

        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'Hasil_Ujian_' . now()->format('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        IOFactory::createWriter($spreadsheet, 'Xlsx')->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $hasil = HasilUjianModel::with(['jadwal', 'user'])->get();

        $pdf = Pdf::loadView('hasil_ujian.export_pdf', compact('hasil'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('Hasil_Ujian_' . now()->format('Ymd_His') . '.pdf');
    }
}
