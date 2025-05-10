<?php

namespace App\Http\Controllers;
use App\Models\MahasiswaModel;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        $data = MahasiswaModel::all(); // ambil semua data dari tabel mahasiswa
        return view('mahasiswa.index', compact('data')); // kirim ke view
    }
}
