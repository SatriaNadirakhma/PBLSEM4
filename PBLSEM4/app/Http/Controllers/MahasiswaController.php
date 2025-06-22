<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MahasiswaModel;
use App\Models\ProdiModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class MahasiswaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Peserta Mahasiswa',
            'list' => ['Home', 'Biodata', 'Peserta Mahasiswa']
        ];

        $page = (object) ['title' => 'Daftar Mahasiswa'];
        $activeMenu = 'peserta-mahasiswa';
        $prodi = ProdiModel::all();

        return view('biodata.mahasiswa.index', compact('breadcrumb', 'page', 'activeMenu', 'prodi'));
    }

    public function list(Request $request)
    {
        $mahasiswa = MahasiswaModel::with('prodi')->select(
            'mahasiswa_id', 'nim', 'nik', 'mahasiswa_nama',
            'angkatan', 'jenis_kelamin', 'status', 'prodi_id'
        );

        if ($request->has('search_query') && $request->search_query != '') {
            $mahasiswa->where('mahasiswa_nama', 'like', '%' . $request->search_query . '%');
        }

        if ($request->has('prodi_id') && $request->prodi_id != '') {
            $mahasiswa->where('prodi_id', $request->prodi_id);
        }

        return DataTables::of($mahasiswa)
            ->addIndexColumn()
            ->addColumn('prodi_id', function ($t) {
                return $t->prodi->prodi_nama ?? '-';
            })
            ->addColumn('aksi', function ($t) {
                $btn = '<button onclick="modalAction(\'' . route('biodata.mahasiswa.show_ajax', $t->mahasiswa_id) . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';
                $btn .= '<button onclick="modalAction(\'' . route('biodata.mahasiswa.edit_ajax', $t->mahasiswa_id) . '\')"  
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';
                $btn .= '<button onclick="modalAction(\'' . route('biodata.mahasiswa.confirm_ajax', $t->mahasiswa_id) . '\')"  
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
        $mahasiswa = MahasiswaModel::find($id);
        return view('biodata.mahasiswa.show_ajax', compact('mahasiswa'));
    }

    public function create_ajax()
    {
        $prodi = ProdiModel::all();
        
        // Sesuai dengan ENUM di migration
        $jenisKelaminEnum = ['Laki-laki', 'Perempuan'];
        $statusEnum = ['aktif', 'alumni'];
        $keteranganEnum = ['gratis', 'berbayar'];
        
        return view('biodata.mahasiswa.create_ajax', compact('prodi', 'jenisKelaminEnum', 'statusEnum', 'keteranganEnum'));
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'nim' => 'required|string|max:10|unique:mahasiswa,nim',
            'nik' => 'required|string|max:16|unique:mahasiswa,nik',
            'mahasiswa_nama' => 'required|string|max:100',
            'angkatan' => 'required|string|max:4',
            'no_telp' => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status' => 'required|in:aktif,alumni',
            'keterangan' => 'nullable|in:gratis,berbayar',
            'prodi_id' => 'required|integer|exists:prodi,prodi_id',
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
            // Ambil semua data dari request
            $data = $request->all();
            
            // Set keterangan otomatis ke 'gratis' jika kosong atau tidak ada
            if (empty($data['keterangan'])) {
                $data['keterangan'] = 'gratis';
            }

            MahasiswaModel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil disimpan',
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
        $mahasiswa = MahasiswaModel::find($id);
        $prodi = ProdiModel::all();

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan'
            ]);
        }

        return view('biodata.mahasiswa.edit_ajax', compact('mahasiswa', 'prodi'));
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
            'nim' => 'required|string|max:20',
            'nik' => 'required|string|max:20',
            'mahasiswa_nama' => 'required|string|max:100',
            'angkatan' => 'required|integer',
            'no_telp' => 'nullable|string',
            'alamat_asal' => 'nullable|string',
            'alamat_sekarang' => 'nullable|string',
            'jenis_kelamin' => 'required|string',
            'status' => 'required|string',
            'keterangan' => 'nullable|string',
            'prodi_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $mahasiswa = MahasiswaModel::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => false,
                'message' => 'Data mahasiswa tidak ditemukan.'
            ]);
        }

        try {
            $mahasiswa->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data mahasiswa berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data mahasiswa.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function confirm_ajax(string $id)
    {
        $mahasiswa = MahasiswaModel::find($id);
        $prodi = ProdiModel::select('prodi_id', 'prodi_nama')->get();
        return view('biodata.mahasiswa.confirm_ajax', compact('mahasiswa', 'prodi'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $mahasiswa = MahasiswaModel::find($id);
            if ($mahasiswa) {
                $mahasiswa->delete();
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
        return view('biodata.mahasiswa.import'); // Ganti view-nya ke mahasiswa
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'file_mahasiswa' => 'required|mimes:xlsx,xls|max:2048'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            try {
                $file = $request->file('file_mahasiswa');

                // Deteksi tipe file secara otomatis
                $reader = IOFactory::createReaderForFile($file->getRealPath());
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);

                $insert = [];
                $errors = [];

                foreach ($data as $rowNumber => $row) {
                    if ($rowNumber > 1) { // Lewati baris header (baris 1)

                        // Abaikan baris kosong total
                        if (collect($row)->filter(fn($val) => trim($val) !== '')->isEmpty()) {
                            continue;
                        }

                        // Ambil dan bersihkan nilai
                        $nim  = trim($row['A'] ?? '');
                        $nik  = trim($row['B'] ?? '');
                        $nama = trim($row['C'] ?? '');

                        if ($nim === '' || $nik === '' || $nama === '') {
                            $errors[] = "Baris {$rowNumber}: NIM, NIK, dan Nama tidak boleh kosong";
                            continue;
                        }

                        // Ambil nilai keterangan dari file Excel atau set default 'gratis'
                        $keterangan = trim($row['J'] ?? '');
                        if (empty($keterangan)) {
                            $keterangan = 'gratis'; // Set default ke 'gratis'
                        }

                        $insert[] = [
                            'nim' => $nim,
                            'nik' => $nik,
                            'mahasiswa_nama' => $nama,
                            'angkatan' => trim($row['D'] ?? ''),
                            'no_telp' => trim($row['E'] ?? ''),
                            'alamat_asal' => trim($row['F'] ?? ''),
                            'alamat_sekarang' => trim($row['G'] ?? ''),
                            'jenis_kelamin' => trim($row['H'] ?? ''),
                            'status' => trim($row['I'] ?? ''),
                            'keterangan' => $keterangan,
                            'prodi_id' => trim($row['J'] ?? ''),
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }

                if (!empty($errors)) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Ada data yang tidak valid: ' . implode(', ', $errors)
                    ]);
                }

                if (count($insert) > 0) {
                    try {
                        MahasiswaModel::insert($insert);
                        return response()->json([
                            'status' => true,
                            'message' => 'Data mahasiswa berhasil diimport'
                        ]);
                    } catch (\Illuminate\Database\QueryException $e) {
                        // Jika terjadi duplikasi, gunakan insertOrIgnore
                        MahasiswaModel::insertOrIgnore($insert);
                        return response()->json([
                            'status' => true,
                            'message' => 'Data mahasiswa berhasil diimport (beberapa data duplikat dilewati)'
                        ]);
                    }
                }

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data valid yang dapat diimport'
                ]);
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Error membaca file Excel: ' . $e->getMessage()
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/');
    }


    public function export_excel()
    {
        $mahasiswa = MahasiswaModel::select(
            'nim', 'nik', 'mahasiswa_nama', 'angkatan', 'no_telp',
            'alamat_asal', 'alamat_sekarang', 'jenis_kelamin',
            'status', 'keterangan', 'prodi_id')
            ->with ('prodi')
            ->orderBy('mahasiswa_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIM');
        $sheet->setCellValue('C1', 'NIK');
        $sheet->setCellValue('D1', 'Nama Mahasiswa');
        $sheet->setCellValue('E1', 'Angkatan');
        $sheet->setCellValue('F1', 'No Telepon');
        $sheet->setCellValue('G1', 'Alamat Asal');
        $sheet->setCellValue('H1', 'Alamat Sekarang');
        $sheet->setCellValue('I1', 'Jenis Kelamin');
        $sheet->setCellValue('J1', 'Status');
        $sheet->setCellValue('K1', 'Keterangan');
        $sheet->setCellValue('L1', 'Prodi');

        $sheet->getStyle('A1:L1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($mahasiswa as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->nim);
            $sheet->setCellValue('C' . $baris, $value->nik);
            $sheet->setCellValue('D' . $baris, $value->mahasiswa_nama);
            $sheet->setCellValue('E' . $baris, $value->angkatan);
            $sheet->setCellValue('F' . $baris, $value->no_telp);
            $sheet->setCellValue('G' . $baris, $value->alamat_asal);
            $sheet->setCellValue('H' . $baris, $value->alamat_sekarang);
            $sheet->setCellValue('I' . $baris, $value->jenis_kelamin);
            $sheet->setCellValue('J' . $baris, $value->status);
            $sheet->setCellValue('K' . $baris, $value->keterangan);
            $sheet->setCellValue('L' . $baris, $value->prodi->prodi_nama ?? '-');
            $baris++;
        }

        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Mahasiswa');
        $filename = 'Data_Mahasiswa_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $mahasiswa = MahasiswaModel::with('prodi')->orderBy('mahasiswa_id')->get();

        $pdf = Pdf::loadView('biodata.mahasiswa.export_pdf', ['mahasiswa' => $mahasiswa]);
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Mahasiswa ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
