<?php

namespace App\Http\Controllers;

use App\Models\DetailPendaftaranModel;
use Illuminate\Http\Request;

class DetailPendaftaranController extends Controller
{
    public function index()
    {
        $data = DetailPendaftaranModel::all();
        return view('detail_pendaftaran.index', compact('data'));
    }
}
