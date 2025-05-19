<?php

namespace App\Http\Controllers;

use App\Models\UjianModel;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function index()
    {
        $data = UjianModel::all(); // ambil semua data dari tabel mahasiswa
        return view('ujian.index', compact('data')); // kirim ke view
    }
}
