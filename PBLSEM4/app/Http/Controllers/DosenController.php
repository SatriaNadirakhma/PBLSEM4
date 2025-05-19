<?php

namespace App\Http\Controllers;

use App\Models\DosenModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DosenController extends Controller
{
    public function index()
    {
        $activeMenu = 'dosen';
        $breadcrumb = (object)[
            'title' => 'Data Dosen',
            'list' => ['Home', 'Biodata', 'Data Dosen']
        ];

        return view('biodata.dosen.index', compact('activeMenu', 'breadcrumb'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $data = DosenModel::select([
                'dosen_id',
                'nidn',
                'nik',
                'dosen_nama',
                'no_telp',
                'alamat_asal',
                'alamat_sekarang',
                'jenis_kelamin',
                'jurusan_id'
            ]);

            return DataTables::of($data)
                ->addIndexColumn()
                ->make(true);
        }

        return response()->json(['message' => 'Forbidden'], 403);
    }
}
