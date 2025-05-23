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
    $tendik = TendikModel::select('tendik_id', 'nip', 'nik', 'tendik_nama', 'jenis_kelamin', 'kampus_id')
        ->with('kampus'); // Pastikan relasi kampus sudah dimuat

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
            $btn = '<button onclick="modalAction(\'' . route('biodata.tendik.show_ajax', $t->tendik_id) . '\')" 
                        class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                        <i class="fa fa-eye me-1"></i> Detail 
                    </button>';

            $btn .= '<button onclick="modalAction(\'' . route('biodata.tendik.edit_ajax', $t->tendik_id) . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                    </button>';

            $btn .= '<button onclick="modalAction(\'' . route('biodata.tendik.confirm_ajax', $t->tendik_id) . '\')"  
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
        $tendik = TendikModel::find($id);
        return view('biodata.tendik.show_ajax', ['tendik' => $tendik]);
    }

    public function create_ajax()
    {
        $kampus = KampusModel::all();
        return view('biodata.tendik.create_ajax', compact('kampus'));
    }


    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nip' => 'required|string|max:20|min:18|unique:tendik,nip',
                'nik' => 'required|string|max:16|min:16|unique:tendik,nik',
                'tendik_nama' => 'required|string|max:100',
                'no_telp' => 'nullable|string|max:13|min:11|',
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
        $kampus = KampusModel::all(); // Ambil semua kampus

        if (!$tendik) {
            return response()->json([
                'status' => false,
                'message' => 'Data tendik tidak ditemukan'
            ]);
        }

        return view('biodata.tendik.edit_ajax', [
        'tendik' => $tendik,
        'kampus' => $kampus // Kirim data kampus ke view
    ]);
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
            'nip' => 'required|string|max:20|min:18',
            'nik' => 'required|string|max:16|min:16',
            'tendik_nama' => 'required|string|max:100',
            'no_telp' => 'nullable|string|max:13|min:11',
            'alamat_asal' => 'nullable|string',
            'alamat_sekarang' => 'nullable|string',
            'jenis_kelamin' => 'required|string',
            'kampus_id' => 'required|integer|exists:kampus,kampus_id',

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

    // Menampilkan form import tendik
    public function import()
    {
        return view('biodata.tendik.import');
    }

    // Import data tendik dari file Excel via AJAX
    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_tendik' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_tendik');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $rowNumber => $row) {
                    if ($rowNumber > 1) { 
                        $insert[] = [
                            'nip' => $row['A'],
                            'nik' => $row['B'],
                            'tendik_nama' => $row['C'],
                            'no_telp' => $row['D'],
                            'alamat_asal' => $row['E'],
                            'alamat_sekarang' => $row['F'],
                            'jenis_kelamin' => $row['G'],
                            'kampus_id' => $row['H'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    TendikModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data tendik berhasil diimport'
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

    //EXPORT KE EXCEL
    public function export_excel()
    {
    $tendik = TendikModel::select('nip', 'nik', 'tendik_nama', 'no_telp', 'alamat_asal', 'alamat_sekarang', 'jenis_kelamin')
        ->orderBy('tendik_nama')
        ->get();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Header kolom
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'NIP');
    $sheet->setCellValue('C1', 'NIK');
    $sheet->setCellValue('D1', 'Nama Tendik');
    $sheet->setCellValue('E1', 'No Telepon');
    $sheet->setCellValue('F1', 'Alamat Asal');
    $sheet->setCellValue('G1', 'Alamat Sekarang');
    $sheet->setCellValue('H1', 'Jenis Kelamin');

    $sheet->getStyle('A1:H1')->getFont()->setBold(true);

    // Isi data
    $no = 1;
    $baris = 2;
    foreach ($tendik as $value) {
        $sheet->setCellValue('A' . $baris, $no++);
        $sheet->setCellValue('B' . $baris, $value->nip);
        $sheet->setCellValue('C' . $baris, $value->nik);
        $sheet->setCellValue('D' . $baris, $value->tendik_nama);
        $sheet->setCellValue('E' . $baris, $value->no_telp);
        $sheet->setCellValue('F' . $baris, $value->alamat_asal);
        $sheet->setCellValue('G' . $baris, $value->alamat_sekarang);
        $sheet->setCellValue('H' . $baris, $value->jenis_kelamin);
        $baris++;
    }

    // Auto size kolom
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $sheet->setTitle('Data Tendik');

    $filename = 'Data_Tendik_' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
    }

    //EXPORT KE PDF
    public function export_pdf()
    {
    $tendik = TendikModel::select('nip', 'nik', 'tendik_nama', 'no_telp', 'alamat_asal', 'alamat_sekarang', 'jenis_kelamin')
        ->orderBy('tendik_nama')
        ->get();

    $pdf = Pdf::loadView('biodata.tendik.export_pdf', ['tendik' => $tendik]);
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption("isRemoteEnabled", true);
    $pdf->render();

    return $pdf->stream('Data Tendik ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
