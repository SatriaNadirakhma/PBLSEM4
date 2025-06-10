<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\PendaftaranModel;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RiwayatController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Pendaftaran',
            'list' => ['Home', 'Riwayat Pendaftaran'],
        ];

        $page = (object) [
            'title' => 'Daftar riwayat pendaftaran mahasiswa',
        ];

        $activeMenu = 'riwayat';

        return view('riwayat.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $riwayat = PendaftaranModel::with(['mahasiswa', 'jadwal', 'detail'])
            ->whereHas('detail', function ($query) use ($request) {
                $query->whereIn('status', ['diterima', 'ditolak']);

                if ($request->status_filter) {
                    $query->where('status', $request->status_filter);
                }
            });

            /// Tambahkan bagian ini untuk mengaktifkan search:
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');

            $riwayat->where(function ($q) use ($search) {
                $q->whereHas('mahasiswa', function ($q2) use ($search) {
                    $q2->where('nim', 'like', "%$search%")
                    ->orWhere('nik', 'like', "%$search%")
                    ->orWhere('mahasiswa_nama', 'like', "%$search%");
                })
                ->orWhere('tanggal_pendaftaran', 'like', "%$search%")
                ->orWhereHas('detail', function ($q2) use ($search) {
                    $q2->where('status', 'like', "%$search%");
                });
            });
        }

        return DataTables::of($riwayat)
            ->addIndexColumn()
            ->addColumn('nim', function($r) {
                return $r->mahasiswa->nim ?? '';
            })
            ->addColumn('nama', function($r) {
                return $r->mahasiswa->mahasiswa_nama ?? '';
            })
            ->addColumn('nik', function($r) {
                return $r->mahasiswa->nik ?? '';
            })
            ->addColumn('tanggal_daftar', function($r) {
                return date('d-m-Y', strtotime($r->tanggal_pendaftaran));
            })
            ->addColumn('status', function($r) {
                $detail = $r->detail_terakhir;
                return $detail ? ucfirst($detail->status) : '';
            })
            ->addColumn('aksi', function ($r) {
                $url = url('/riwayat/' . $r->pendaftaran_id . '/show_ajax');
                return '<button onclick="modalAction(\'' . $url . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function show_ajax($id)
    {
        $pendaftaran = PendaftaranModel::with([
            'mahasiswa.prodi.jurusan.kampus',
            'detail',
            'jadwal'
        ])->find($id);

        return view('riwayat.show_ajax', compact('pendaftaran'));
    }

    public function export_pdf(Request $request)
    {
        $status = $request->status;
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $query = PendaftaranModel::with(['mahasiswa.prodi.jurusan.kampus', 'jadwal', 'detail']);

        if ($tanggalAwal && $tanggalAkhir) {
            $query->whereBetween('tanggal_pendaftaran', [$tanggalAwal, $tanggalAkhir]);
        } elseif ($tanggalAwal) {
            $query->whereDate('tanggal_pendaftaran', '>=', $tanggalAwal);
        } elseif ($tanggalAkhir) {
            $query->whereDate('tanggal_pendaftaran', '<=', $tanggalAkhir);
        }

        if ($status == 'diterima' || $status == 'ditolak') {
            $query->whereHas('detail', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $pendaftaran = $query->get();

        $pdf = Pdf::loadView('riwayat.export_pdf', ['pendaftaran' => $pendaftaran])
            ->setPaper('a4', 'landscape')
            ->setOptions(['isRemoteEnabled' => true]);

        return $pdf->stream('Data Pendaftaran ' . date('Y-m-d H-i-s') . '.pdf');
    }

    public function export_excel(Request $request)
    {
        $status = $request->status;
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;

        $query = PendaftaranModel::with(['mahasiswa.prodi.jurusan.kampus', 'jadwal', 'detail']);

        if ($tanggalAwal && $tanggalAkhir) {
            $query->whereBetween('tanggal_pendaftaran', [$tanggalAwal, $tanggalAkhir]);
        } elseif ($tanggalAwal) {
            $query->whereDate('tanggal_pendaftaran', '>=', $tanggalAwal);
        } elseif ($tanggalAkhir) {
            $query->whereDate('tanggal_pendaftaran', '<=', $tanggalAkhir);
        }

        if (in_array($status, ['diterima', 'ditolak'])) {
            $query->whereHas('detail', fn($q) => $q->where('status', $status));
        }

        $pendaftaran = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Riwayat Pendaftaran');

        $sheet->setCellValue('A1', 'LAPORAN RIWAYAT PENDAFTARAN MAHASISWA');
        $sheet->mergeCells('A1:N1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);

        $row = 3;
        if ($status && $status != 'semua') {
            $sheet->setCellValue('A' . $row++, 'Status: ' . ucfirst($status));
        }
        if ($tanggalAwal) {
            $sheet->setCellValue('A' . $row++, 'Tanggal Awal: ' . date('d-m-Y', strtotime($tanggalAwal)));
        }
        if ($tanggalAkhir) {
            $sheet->setCellValue('A' . $row++, 'Tanggal Akhir: ' . date('d-m-Y', strtotime($tanggalAkhir)));
        }
        $row++;

        $headers = ['A' => 'No','B' => 'NIM','C' => 'Nama Mahasiswa','D' => 'NIK','E' => 'No Telp','F' => 'Alamat Asal','G' => 'Alamat Sekarang','H' => 'Program Studi','I' => 'Jurusan','J' => 'Kampus','K' => 'Scan KTP','L' => 'Scan KTM','M' => 'Pas Foto','N' => 'Tanggal Pendaftaran'];
        foreach ($headers as $col => $text) {
            $sheet->setCellValue($col . $row, $text);
        }
        $sheet->getStyle('A'.$row.':N'.$row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ]);

        $row++;
        $no = 1;
        $imageRowHeight = 60;
        foreach ($pendaftaran as $data) {
            $sheet->getRowDimension($row)->setRowHeight($imageRowHeight);
            $sheet->setCellValue("A$row", $no++)
                ->setCellValue("B$row", $data->mahasiswa->nim ?? '-')
                ->setCellValue("C$row", $data->mahasiswa->mahasiswa_nama ?? '-')
                ->setCellValue("D$row", $data->mahasiswa->nik ?? '-')
                ->setCellValue("E$row", $data->mahasiswa->no_telp ?? '-')
                ->setCellValue("F$row", $data->mahasiswa->alamat_asal ?? '-')
                ->setCellValue("G$row", $data->mahasiswa->alamat_sekarang ?? '-')
                ->setCellValue("H$row", $data->mahasiswa->prodi->prodi_nama ?? '-')
                ->setCellValue("I$row", $data->mahasiswa->prodi->jurusan->jurusan_nama ?? '-')
                ->setCellValue("J$row", $data->mahasiswa->prodi->jurusan->kampus->kampus_nama ?? '-')
                ->setCellValue("N$row", date('d-m-Y', strtotime($data->tanggal_pendaftaran)));

            $this->addImageToCell($sheet, $data->mahasiswa->scan_ktp, 'K' . $row, 'KTP');
            $this->addImageToCell($sheet, $data->mahasiswa->scan_ktm, 'L' . $row, 'KTM');
            $this->addImageToCell($sheet, $data->mahasiswa->pas_foto, 'M' . $row, 'Foto');

            $sheet->getStyle("A$row:N$row")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);

            $row++;
        }

        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Data_Pendaftaran_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $writer->save('php://output');
    }

    private function addImageToCell($sheet, $path, $cell, $name)
    {
        if ($path && file_exists(public_path('uploads/' . $path))) {
            $drawing = new Drawing();
            $drawing->setPath(public_path('uploads/' . $path));
            $drawing->setHeight(60);
            $drawing->setCoordinates($cell);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);
            $drawing->setWorksheet($sheet);
        } else {
            $sheet->setCellValue($cell, 'Tidak Ada');
        }
    }

    public function show_export_form()
    {
        return view('riwayat.export_form');
    }
}