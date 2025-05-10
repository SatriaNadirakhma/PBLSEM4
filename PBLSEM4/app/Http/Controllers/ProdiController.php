<?php

namespace App\Http\Controllers;
use App\Models\ProdiModel;

use Illuminate\Http\Request;

class ProdiController extends Controller
{
    
    public function index()
    {
        $data = ProdiModel::all(); // ambil semua data dari tabel prodi
        return view('prodi.index', compact('data')); // kirim ke view
    }
}
