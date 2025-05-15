<?php

namespace App\Http\Controllers;

use App\Models\TendikModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TendikController extends Controller
{
    public function index()
    {
        $activeMenu = 'peserta-tendik';

        // Membuat objek breadcrumb agar view breadcrumb tidak error
        $breadcrumb = (object)[
            'title' => 'Peserta Tendik',
            'list' => ['Home', 'Biodata', 'Peserta Tendik']
        ];

        return view('biodata.tendik.index', compact('activeMenu', 'breadcrumb'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = TendikModel::select(['tendik_id', 'tendik_nama', 'nip', 'nik', 'no_telp', 'alamat_asal', 'alamat_sekarang', 'jenis_kelamin', 'kampus_id']);
            return DataTables::of($data)->addIndexColumn()->make(true);
        }
    }

}

