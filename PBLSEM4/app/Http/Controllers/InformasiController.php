<?php

namespace App\Http\Controllers;

use App\Models\InformasiModel;
use Illuminate\Http\Request;

class InformasiController extends Controller
{
    public function index()
    {
        $data = InformasiModel::all(); // ambil semua data dari tabel mahasiswa
        return view('informasi.index', compact('data')); // kirim ke view
    }
}
