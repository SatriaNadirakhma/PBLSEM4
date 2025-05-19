<?php

namespace App\Http\Controllers;

use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    public function index()
    {
        $activeMenu = 'mahasiswa';
        $breadcrumb = (object)[
            'title' => 'Data Mahasiswa',
            'list' => ['Home', 'Biodata', 'Data Mahasiswa']
        ];

        return view('biodata.mahasiswa.index', compact('activeMenu', 'breadcrumb'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = MahasiswaModel::select([
                'mahasiswa_id',
                'nim',
                'nik',
                'mahasiswa_nama',
                'angkatan',
                'no_telp',
                'alamat_asal',
                'alamat_sekarang',
                'jenis_kelamin',
                'status',
                'keterangan',
                'prodi_id'
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
