<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\TendikModel;
use Illuminate\Http\Request;

class DataDiriController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $breadcrumb = (object) [
            'title' => 'Data Diri',
            'list' => ['Home', 'Data Diri'],
        ];

        $page = (object) [
            'title' => 'Informasi Data Diri',
        ];

        $activeMenu = 'datadiri';

        switch ($user->role) {
            case 'mahasiswa':
                $mahasiswa = MahasiswaModel::where('mahasiswa_id', $user->mahasiswa_id)->firstOrFail();
                return view('datadiri.mahasiswa.index', compact('breadcrumb', 'page', 'activeMenu', 'mahasiswa'));

            case 'dosen':
                $dosen = DosenModel::where('dosen_id', $user->dosen_id)->firstOrFail();
                return view('datadiri.dosen.index', compact('breadcrumb', 'page', 'activeMenu', 'dosen'));

            case 'tendik':
                $tendik = TendikModel::where('tendik_id', $user->tendik_id)->firstOrFail();
                return view('datadiri.tendik.index', compact('breadcrumb', 'page', 'activeMenu', 'tendik'));

            default:
                abort(403, 'Role tidak dikenali');
        }
    }

    public function updateMahasiswa(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|string|max:20',
            'alamat_asal' => 'required|string|max:255',
            'alamat_sekarang' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = MahasiswaModel::where('mahasiswa_id', $user->mahasiswa_id)->firstOrFail();

        $mahasiswa->update([
            'no_telp' => $request->no_telp,
            'alamat_asal' => $request->alamat_asal,
            'alamat_sekarang' => $request->alamat_sekarang,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function updateDosen(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|string|max:20',
            'alamat_asal' => 'required|string|max:255',
            'alamat_sekarang' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $dosen = DosenModel::where('dosen_id', $user->dosen_id)->firstOrFail();

        $dosen->update([
            'no_telp' => $request->no_telp,
            'alamat_asal' => $request->alamat_asal,
            'alamat_sekarang' => $request->alamat_sekarang,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function updateTendik(Request $request)
    {
        $request->validate([
            'no_telp' => 'required|string|max:20',
            'alamat_asal' => 'required|string|max:255',
            'alamat_sekarang' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $tendik = TendikModel::where('tendik_id', $user->tendik_id)->firstOrFail();

        $tendik->update([
            'no_telp' => $request->no_telp,
            'alamat_asal' => $request->alamat_asal,
            'alamat_sekarang' => $request->alamat_sekarang,
        ]);

        return response()->json(['message' => 'Data berhasil diperbarui']);
    }



}


