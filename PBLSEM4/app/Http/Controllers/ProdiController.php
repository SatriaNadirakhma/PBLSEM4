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
        
        return view('prodi.show_ajax', [
            'prodi' => $prodi,
            'jurusan' => JurusanModel::find($prodi->jurusan_id)
        ]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        $jurusan = JurusanModel::all();
        return view('prodi.create_ajax', ['jurusan' => $jurusan]);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi_kode' => 'required|string|max:20|unique:prodi,prodi_kode',
                'prodi_nama' => 'required|string|max:100',
                'jurusan_id' => 'required|exists:jurusan,jurusan_id',
            ];

            $validator = Validator::make($request->all(), $rules);
                
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
                
            try {
                // Menyimpan data kampus
                ProdiModel::create($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data kampus berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                    ]);
                }
            }

        return redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $prodi = ProdiModel::find($id);
        $jurusan = JurusanModel::select('jurusan_id', 'jurusan_nama')->get();
        
        return view('prodi.edit_ajax', ['prodi' => $prodi, 'jurusan' => $jurusan]);
    }

    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'prodi_kode' => 'required|string|max:20|unique:prodi,prodi_kode,' . $id . ',prodi_id',
                'prodi_nama' => 'required|string|max:100',
                'jurusan_id' => 'required|exists:jurusan,jurusan_id',
            ];

            $validator = Validator::make($request->all(), $rules);
                
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
                
            try {
                // Menyimpan data kampus
                ProdiModel::find($id)->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data kampus berhasil disimpan',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                        'status' => false,
                        'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                    ]);
                }
            }

        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $prodi = ProdiModel::find($id);
        return view('prodi.confirm_ajax', ['prodi' => $prodi]);
    }

    public function delete_ajax(string $id)
    {
        $prodi = ProdiModel::find($id);
        return view('prodi.delete_ajax', ['prodi' => $prodi]);
    }
}
