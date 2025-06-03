<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PendaftaranModel;

class RiwayatPesertaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Pendaftaran',
            'list' => ['Home', 'Riwayat Pendaftaran'],
        ];

        $page = (object) ['title' => 'Riwayat Pendaftaran'];
        $activeMenu = 'riwayatPeserta';

        // Mengambil user yang sedang login
        $user = Auth::user(); 

        // Cek apakah user sudah login
        if (!$user) {
            return redirect()->route('user.login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Cek apakah user punya mahasiswa_id
        if (!$user->mahasiswa_id) {
            return redirect()->back()->with('error', 'Akun Anda tidak terkait dengan data mahasiswa');
        }

        // Ambil riwayat pendaftaran berdasarkan mahasiswa_id dari user
        $riwayat = PendaftaranModel::with(['mahasiswa', 'jadwal', 'detail'])
                ->where('mahasiswa_id', $user->mahasiswa_id)
                ->whereHas('detail', function($query) {
                    $query->whereIn('status', ['diterima', 'ditolak']);
                })
                ->orderByDesc('tanggal_pendaftaran')
                ->paginate(10);

        return view('riwayatPeserta.index', compact('breadcrumb', 'page', 'activeMenu', 'riwayat'));
    }

    public function show($id)
    {
        $user = Auth::user(); // user yang sedang login

        // Cek apakah user punya mahasiswa_id
        if (!$user->mahasiswa_id) {
            return redirect()->back()->with('error', 'Akun Anda tidak terkait dengan data mahasiswa');
        }

        // Cari pendaftaran berdasarkan ID dan pastikan itu milik mahasiswa yang login
        // Include semua relasi yang diperlukan
        $item = PendaftaranModel::with([
                    'mahasiswa' => function($query) {
                        $query->with(['prodi' => function($q) {
                            $q->with(['jurusan' => function($qu) {
                                $qu->with('kampus');
                            }]);
                        }]);
                    },
                    'jadwal', 
                    'detail'
                ])
                ->where('mahasiswa_id', $user->mahasiswa_id)
                ->where('pendaftaran_id', $id)
                ->firstOrFail();

        $breadcrumb = (object) [
            'title' => 'Detail Riwayat Pendaftaran',
            'list' => ['Home', 'Riwayat Pendaftaran', 'Detail'],
        ];

        $page = (object) ['title' => 'Detail Riwayat Pendaftaran'];
        $activeMenu = 'riwayatPeserta';

        return view('riwayatPeserta.show', compact('item', 'breadcrumb', 'page', 'activeMenu'));
    }
}
