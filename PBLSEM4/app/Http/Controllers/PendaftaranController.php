<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranModel;

class PendaftaranController extends Controller
{
    public function index()
    {
        $data = PendaftaranModel::all(); // ambil semua data dari tabel mahasiswa
        return view('pendaftaran.index', compact('data')); // kirim ke view
    }
}
