<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranModel;
use App\Models\DetailPendaftaranModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\KirimPesanController;

class VerifikasiPendaftaranController extends Controller
{
    private $kirimPesanController;

    // Inject KirimPesanController melalui constructor
    public function __construct(KirimPesanController $kirimPesanController)
    {
        $this->kirimPesanController = $kirimPesanController;
    }

    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Verifikasi Pendaftaran',
            'list' => ['Home', 'Verifikasi'],
        ];

        $page = (object) [
            'title' => 'Pendaftaran yang menunggu verifikasi',
        ];

        $activeMenu = 'verifikasi';

        $registrationStatus = Cache::rememberForever('registration_status', function () {
            return Config::get('app_settings.registration_open') ? 'open' : 'closed';
        });

        return view('verifikasi.index', compact('breadcrumb', 'page', 'activeMenu', 'registrationStatus'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = PendaftaranModel::whereHas('detail', function($query) {
                                    $query->where('status', 'menunggu');
                                })
                                ->with(['mahasiswa', 'detail'])
                                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nim', fn($row) => $row->mahasiswa->nim ?? '-')
                ->addColumn('nik', fn($row) => $row->mahasiswa->nik ?? '-')
                ->addColumn('nama', fn($row) => $row->mahasiswa->mahasiswa_nama ?? '-')
                ->addColumn('prodi', fn($row) => $row->mahasiswa->prodi->prodi_nama ?? '-')
                ->addColumn('jurusan', fn($row) => $row->mahasiswa->prodi->jurusan->jurusan_nama ?? '-')
                ->addColumn('kampus', fn($row) => $row->mahasiswa->prodi->jurusan->kampus->kampus_nama ?? '-')
                ->addColumn('aksi', function ($row) {
                    $url = route('verifikasi.show', $row->pendaftaran_id);

                    return '
                        <button onclick="modalAction(\'' . $url . '\')" class="btn btn-info btn-sm me-1">Detail</button>
                    ';
                })
                ->addColumn('status', function($row) {
                    $detail = $row->detail;
                    $status = strtolower($detail->status ?? 'menunggu');
                    $btnClass = match($status) {
                        'menunggu' => 'btn-primary',
                        'diterima' => 'btn-success',
                        'ditolak' => 'btn-danger',
                        default => 'btn-secondary'
                    };

                    return '
                    <div class="dropdown">
                        <button class="btn btn-sm '.$btnClass.' dropdown-toggle" type="button" data-toggle="dropdown">
                            '.ucfirst($status).'
                        </button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-header">-- Pilih Pembaruan Status --</li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus('.$detail->detail_id.', \'menunggu\')">Menunggu</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus('.$detail->detail_id.', \'diterima\')">Diterima</a></li>
                            <li><a class="dropdown-item" href="#" onclick="updateStatus('.$detail->detail_id.', \'ditolak\')">Ditolak</a></li>
                        </ul>
                    </div>';
                })
                ->rawColumns(['aksi', 'status'])
                ->make(true);
        }
    }

    public function update(Request $request, $id)
    {
        $detail = DetailPendaftaranModel::with('pendaftaran.mahasiswa')->findOrFail($id); // Load relasi mahasiswa
        $oldStatus = $detail->status; // Store the old status

        DB::beginTransaction(); // Mulai transaksi DB

        try {
            $detail->update([
                'status' => $request->status,
                'catatan' => $request->catatan,
            ]);

            // Jika status adalah "diterima", ubah keterangan mahasiswa menjadi "berbayar"
            if ($request->status === 'diterima' && $detail->pendaftaran && $detail->pendaftaran->mahasiswa) {
                $detail->pendaftaran->mahasiswa->update([
                    'keterangan' => 'berbayar',
                ]);
            }

            // KONDISI PENTING: Hanya kirim WA jika status berubah dari 'menunggu' ke 'diterima' atau 'ditolak'
            if ($oldStatus === 'menunggu' && in_array($request->status, ['diterima', 'ditolak'])) {
                $pendaftaran = $detail->pendaftaran;
                $mahasiswa = $pendaftaran->mahasiswa;
                $nomor = $mahasiswa->no_telp;
                $nama = $mahasiswa->mahasiswa_nama;
                $status = ucfirst($request->status); // Get the updated status
                $catatan = $request->catatan;

                $pesan = "SIPINTA POLINEMA\n------------------------------\nHalo $nama,\n";

                if ($request->status === 'diterima') {
                    $pesan .= "Selamat! Pendaftaran Anda untuk tes TOEIC telah *DITERIMA*. Silakan cek informasi lebih lanjut melalui portal SIPINTA.\n\n📅 Pastikan Anda mengikuti jadwal tes dengan tepat waktu.\n📌 Persiapkan persyaratan yang diperlukan saat hari pelaksanaan ujian.\n";
                } elseif ($request->status === 'ditolak') {
                    $pesan .= "Mohon maaf, pendaftaran Anda untuk tes TOEIC telah *DITOLAK*.\n\nSilakan cek kembali data yang Anda kirimkan di portal SIPINTA atau hubungi admin untuk informasi lebih lanjut.\n\nTerima kasih atas pengertiannya.\n";
                }

                if ($catatan) {
                    $pesan .= "Catatan: $catatan\n";
                }

                $pesan .= "\nTerima kasih.\n— Admin SIPINTA POLINEMA";

                // Panggil metode kirim dari KirimPesanController
                $wa_response = $this->kirimPesanController->kirim($pendaftaran->pendaftaran_id, $nomor, $pesan);

                // Jika pengiriman WA berhasil (sesuai respons Fonnte), simpan ke Local Storage via response
                // Kita akan mengirimkan status pengiriman WA ke frontend
                DB::commit(); // Commit transaksi DB sebelum mengirim respons
                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diperbarui dan pesan WA dikirim!',
                    'pendaftaran_id' => $pendaftaran->pendaftaran_id, // Kirim ID pendaftaran
                    'pengiriman_status_wa' => $wa_response['pengiriman_status'] // Kirim status pengiriman WA
                ]);
            }

            DB::commit(); // Commit transaksi DB jika tidak mengirim WA
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui!']);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $detail = DetailPendaftaranModel::with('pendaftaran.mahasiswa')->findOrFail($id);

        return view('verifikasi.edit_ajax', compact('detail'));
    }

    public function show($id)
    {
        $pendaftaran = PendaftaranModel::with([
            'mahasiswa.prodi.jurusan.kampus',
            'detail',
            'jadwal'
        ])->findOrFail($id);

        return view('verifikasi.show_ajax', compact('pendaftaran'));
    }

    public function verifyAll(Request $request)
    {
        DB::beginTransaction(); // Mulai transaksi DB

        try {
            $catatan = $request->input('catatan', '');

            $details = DetailPendaftaranModel::where('status', 'menunggu')
                ->with(['pendaftaran.mahasiswa'])
                ->get();

            $count = 0;
            $wa_statuses = []; // Array untuk menyimpan status WA dari setiap pengiriman

            foreach ($details as $detail) {
                // Simpan status lama sebelum update
                $oldStatus = $detail->status;

                $detail->update([
                    'status' => 'diterima',
                    'catatan' => $catatan,
                ]);

                if ($detail->pendaftaran && $detail->pendaftaran->mahasiswa) {
                    $mahasiswa = $detail->pendaftaran->mahasiswa;
                    if ($mahasiswa->keterangan === 'gratis') {
                        $mahasiswa->update([
                            'keterangan' => 'berbayar',
                        ]);
                    }

                    // Hanya kirim WA jika status berubah dari 'menunggu' ke 'diterima'
                    if ($oldStatus === 'menunggu' && $detail->status === 'diterima') {
                        $nomor = $mahasiswa->no_telp;
                        $nama = $mahasiswa->mahasiswa_nama;
                        $pesan = "SIPINTA POLINEMA\n------------------------------\nHalo $nama,\n\nSelamat! Pendaftaran Anda untuk tes TOEIC telah *DITERIMA*. Silakan cek informasi lebih lanjut melalui portal SIPINTA.\n\n📅 Pastikan Anda mengikuti jadwal tes dengan tepat waktu.\n📌 Persiapkan persyaratan yang diperlukan saat hari pelaksanaan ujian.\n\n";
                        if ($catatan) {
                            $pesan .= "Catatan: $catatan\n";
                        }
                        $pesan .= "\nTerima kasih.\n— Admin SIPINTA POLINEMA";

                        $wa_response = $this->kirimPesanController->kirim($detail->pendaftaran->pendaftaran_id, $nomor, $pesan);
                        
                        // Simpan status WA untuk dikirim ke frontend
                        $wa_statuses[] = [
                            'pendaftaran_id' => $detail->pendaftaran->pendaftaran_id,
                            'pengiriman_status' => $wa_response['pengiriman_status']
                        ];
                    }
                }
                $count++;
            }

            DB::commit(); // Commit transaksi DB

            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => "Berhasil memverifikasi {$count} data",
                'wa_statuses' => $wa_statuses // Kirim array status WA
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}