<?php

namespace App\Http\Controllers;

use App\Models\ProdiModel;
use Illuminate\Http\Request;
use App\Models\JurusanModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Program Studi',
            'list' => ['Home', 'Program Studi'],
        ];

        $page = (object) [
            'title' => 'Daftar program studi yang terdaftar dalam sistem',
        ];

        $activeMenu = 'prodi';

        $jurusan = JurusanModel::all();

        return view('prodi.index', compact('breadcrumb', 'page', 'activeMenu', 'jurusan'));
    }

    public function list(Request $request)
    {
        $prodi = ProdiModel::select('prodi_id', 'prodi_kode', 'prodi_nama', 'jurusan_id')
            ->with('jurusan');

       if ($request->has('search_query') && $request->search_query != '') {
        $prodi->where('prodi_nama', 'like', '%' . $request->search_query . '%');
        }

        if ($request->jurusan_id) {
            $prodi->where('jurusan_id', $request->jurusan_id);
        }

        return DataTables::of($prodi)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($k) {
                // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/prodi/' . $k->prodi_id . '/show_ajax') . '\')" class="btn btn-info btn-sm me-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $k->prodi_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm me-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/prodi/' . $k->prodi_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

    public function show_ajax(string $id)
    {
        $prodi = ProdiModel::find($id);
        return view('prodi.show_ajax', ['prodi' => $prodi]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        return view('prodi.create_ajax');
    }

}
