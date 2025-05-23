<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\MahasiswaModel;

class DataDiriController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil data mahasiswa berdasarkan mahasiswa_id dari user
        $mahasiswa = MahasiswaModel::where('mahasiswa_id', $user->mahasiswa_id)->firstOrFail();

        $breadcrumb = (object) [
            'title' => 'Data Diri Mahasiswa',
            'list' => ['Home', 'Data Diri'],
        ];

        $page = (object) [
            'title' => 'Informasi Data Diri Mahasiswa',
        ];

        $activeMenu = 'datadiri';

        return view('datadiri.index', compact('breadcrumb', 'page', 'activeMenu', 'mahasiswa'));
    }
}


