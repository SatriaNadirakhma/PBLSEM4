<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JurusanModel;

class JurusanController extends Controller
{
    public function index()
    {
        $data = JurusanModel::all(); // ambil semua data dari tabel jurusan
        return view('jurusan.index', compact('data')); // kirim ke view
    }
}
