<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilUjianModel;
use App\Models\JadwalModel;
use App\Models\UserModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class HasilUjianController extends Controller
{
    // Menampilkan halaman utama hasil ujian
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Hasil Ujian',
            'list' => ['Home', 'Hasil Ujian'],
        ];

        $page = (object) [
            'title' => 'Daftar hasil ujian peserta TOEIC',
        ];

        $activeMenu = 'hasil_ujian';

        return view('hasil_ujian.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Mengambil data hasil ujian untuk DataTables
    public function list(Request $request)
    {
        $hasilUjian = HasilUjianModel::with([
            'jadwal',
            'user.mahasiswa',
            'user.dosen',
            'user.tendik',
        ])->select('hasil_ujian.*');

        if ($request->has('search_query') && $request->search_query != '') {
            $hasilUjian->where(function($q) use ($request) {
                $q->whereHas('user.mahasiswa', function($q2) use ($request) {
                    $q2->where('mahasiswa_nama', 'like', '%' . $request->search_query . '%');
                })
                ->orWhereHas('user.dosen', function($q3) use ($request) {
                    $q3->where('dosen_nama', 'like', '%' . $request->search_query . '%');
                })
                ->orWhereHas('user.tendik', function($q4) use ($request) {
                    $q4->where('tendik_nama', 'like', '%' . $request->search_query . '%');
                });
            });
        }

        return DataTables::of($hasilUjian)
            ->addIndexColumn()
            ->addColumn('nama', function ($h) {
                $user = $h->user;
                if ($user) {
                    if ($user->mahasiswa) {
                        return $user->mahasiswa->mahasiswa_nama;
                    } elseif ($user->dosen) {
                        return $user->dosen->dosen_nama;
                    } elseif ($user->tendik) {
                        return $user->tendik->tendik_nama;
                    } else {
                        return $user->nama ?? '-';
                    }
                }
                return '-';
            })
            ->addColumn('tanggal_pelaksanaan', function ($h) {
                return $h->jadwal ? \Carbon\Carbon::parse($h->jadwal->tanggal_pelaksanaan)->format('d/m/Y') : '-';
            })
            ->addColumn('nilai_listening', fn($h) => $h->nilai_listening ?? 0)
            ->addColumn('nilai_reading', fn($h) => $h->nilai_reading ?? 0)
            ->addColumn('nilai_total', fn($h) => $h->nilai_total ?? 0)
            ->addColumn('status_lulus', function($h) {
                $status = $h->status_lulus ?? 'Tidak Lulus';
                $badgeClass = $status == 'Lulus' ? 'badge bg-success' : 'badge bg-danger';
                return '<span class="' . $badgeClass . '">' . $status . '</span>';
            })
            ->addColumn('role', function ($h) {
                return $h->user ? ucfirst($h->user->role) : '-';
            })
            ->addColumn('aksi', function ($h) {
                $btn  = '<button onclick="modalAction(\'' . url('/hasil-ujian/' . $h->hasil_id . '/show_ajax') . '\')" 
                            class="btn btn-info btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-eye me-1"></i> Detail
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/hasil-ujian/' . $h->hasil_id . '/edit_ajax') . '\')" 
                            class="btn btn-warning btn-sm rounded-pill shadow-sm me-1 px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-edit me-1"></i> Edit
                        </button>';

                $btn .= '<button onclick="modalAction(\'' . url('/hasil-ujian/' . $h->hasil_id . '/delete_ajax') . '\')" 
                            class="btn btn-danger btn-sm rounded-pill shadow-sm px-3 py-1" style="font-size: 0.85rem;">
                            <i class="fa fa-trash me-1"></i> Hapus
                        </button>';

                return $btn;
            })
            ->rawColumns(['aksi', 'status_lulus'])
            ->make(true);
    }

    // Menampilkan detail hasil ujian
    public function show_ajax($id)
    {
        $hasilUjian = HasilUjianModel::with(['jadwal', 'user.mahasiswa', 'user.dosen', 'user.tendik'])->find($id);
        if (!$hasilUjian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return view('hasil_ujian.show_ajax', compact('hasilUjian'));
    }

    // Form tambah data
    public function create_ajax()
    {
        $jadwal = JadwalModel::all();
        $users = UserModel::all();
        return view('hasil_ujian.create_ajax', compact('jadwal', 'users'));
    }

    // Menyimpan data hasil ujian
    public function store_ajax(Request $request)
    {
        $rules = [
            'nilai_listening' => 'required|integer|min:0|max:495',
            'nilai_reading' => 'required|integer|min:0|max:495',
            'jadwal_id' => 'required|exists:jadwal,jadwal_id',
            'user_id' => 'required|exists:users,id',
            'catatan' => 'nullable|string|max:255',
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
            $hasilUjian = new HasilUjianModel();
            $hasilUjian->nilai_listening = $request->input('nilai_listening');
            $hasilUjian->nilai_reading = $request->input('nilai_reading');
            $hasilUjian->nilai_total = $request->input('nilai_listening') + $request->input('nilai_reading');
            $hasilUjian->status_lulus = $hasilUjian->nilai_total >= 600 ? 'Lulus' : 'Tidak Lulus';
            $hasilUjian->jadwal_id = $request->input('jadwal_id');
            $hasilUjian->user_id = $request->input('user_id');
            $hasilUjian->catatan = $request->input('catatan');
            $hasilUjian->save();

            return response()->json([
                'status' => true,
                'message' => 'Data hasil ujian berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
            ]);
        }
    }

    // Form edit data
    public function edit_ajax($id)
    {
        $hasilUjian = HasilUjianModel::find($id);
        if (!$hasilUjian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        $jadwal = JadwalModel::all();
        $users = UserModel::all();
        return view('hasil_ujian.edit_ajax', compact('hasilUjian', 'jadwal', 'users'));
    }

    // Memperbarui data hasil ujian
    public function update_ajax(Request $request, $id)
    {
        $rules = [
            'nilai_listening' => 'required|integer|min:0|max:495',
            'nilai_reading' => 'required|integer|min:0|max:495',
            'jadwal_id' => 'required|exists:jadwal,jadwal_id',
            'user_id' => 'required|exists:users,id',
            'catatan' => 'nullable|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $hasilUjian = HasilUjianModel::find($id);
        if (!$hasilUjian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }

        try {
            $hasilUjian->update([
                'nilai_listening' => $request->input('nilai_listening'),
                'nilai_reading' => $request->input('nilai_reading'),
                'nilai_total' => $request->input('nilai_listening') + $request->input('nilai_reading'),
                'status_lulus' => ($request->input('nilai_listening') + $request->input('nilai_reading')) >= 600 ? 'Lulus' : 'Tidak Lulus',
                'jadwal_id' => $request->input('jadwal_id'),
                'user_id' => $request->input('user_id'),
                'catatan' => $request->input('catatan'),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data hasil ujian berhasil diperbarui',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage(),
            ]);
        }
    }

    // Form konfirmasi hapus
    public function confirm_ajax($id)
    {
        $hasilUjian = HasilUjianModel::with(['jadwal', 'user.mahasiswa', 'user.dosen', 'user.tendik'])->find($id);
        if (!$hasilUjian) {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
        return view('hasil_ujian.confirm_ajax', compact('hasilUjian'));
    }

    // Menghapus data hasil ujian
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $hasilUjian = HasilUjianModel::find($id);
            if (!$hasilUjian) {
                return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
            }

            try {
                $hasilUjian->delete();
                return response()->json(['status' => true, 'message' => 'Data berhasil dihapus']);
            } catch (\Exception $e) {
                return response()->json(['status' => false, 'message' => 'Gagal menghapus data: ' . $e->getMessage()]);
            }
        }
        
        return redirect('/');

        
    }

    
}