<?php

namespace App\Http\Controllers;

use App\Models\HasilUjianModel;
use Illuminate\Http\Request;

class HasilUjianController extends Controller
{
    public function index()
    {
        $data = HasilUjianModel::all(); // ambil semua data dari tabel mahasiswa
        return view('hasil_ujian.index', compact('data')); // kirim ke view
    }
}
