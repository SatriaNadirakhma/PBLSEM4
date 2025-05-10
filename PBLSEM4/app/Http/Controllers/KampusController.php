<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KampusModel;

class KampusController extends Controller
{
    public function index()
    {
        $data = KampusModel::all(); // ambil semua data dari tabel kampus
        return view('kampus.index', compact('data')); // kirim ke view
    }
}
