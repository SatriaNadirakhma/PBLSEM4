<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KampusModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class KampusController extends Controller
{
    // Menampilkan halaman utama kampus
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kampus',
            'list' => ['Home', 'Kampus'],
        ];

        $page = (object) [
            'title' => 'Daftar kampus yang terdaftar dalam sistem',
        ];

        $activeMenu = 'kampus';

        return view('kampus.index', compact('breadcrumb', 'page', 'activeMenu'));
    }


    // Mengambil data kampus untuk DataTables
    public function list(Request $request)
    {
        $kampus = KampusModel::select('kampus_id', 'kampus_kode', 'kampus_nama');

       if ($request->has('search_query') && $request->search_query != '') {
        $kampus->where('kampus_nama', 'like', '%' . $request->search_query . '%');
    }

        return DataTables::of($kampus)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($k) {
                // Menambahkan kolom aksi
                $btn = '<button onclick="modalAction(\'' . url('/kampus/' . $k->kampus_id . '/show_ajax') . '\')" class="btn btn-info btn-sm me-1">Detail</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/kampus/' . $k->kampus_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm me-1">Edit</button>';
                $btn .= '<button onclick="modalAction(\'' . url('/kampus/' . $k->kampus_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
            ->make(true);
    }

     //Show AJAX
    public function show_ajax(string $id)
    {
        $kampus = KampusModel::find($id);
        return view('kampus.show_ajax', ['kampus' => $kampus]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        return view('kampus.create_ajax');
    }

    // Store ajax
        public function store_ajax(Request $request)
        {
            if ($request->ajax() || $request->wantsJson()) {
                $rules = [
                    'kampus_kode' => 'required|string|max:20|unique:kampus,kampus_kode',
                    'kampus_nama' => 'required|string|max:100',
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
                    KampusModel::create($request->all());

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

    // Confirm ajax
    public function confirm_ajax(string $id){

        $kampus = KampusModel::find($id);
        return view('kampus.confirm_ajax', ['kampus' => $kampus]);
    }

    // Delete ajax
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $kampus = KampusModel::find($id);
            if ($kampus) {
                $kampus->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    //Edit AJAX
    public function edit_ajax(string $id)
    {
        $kampus = KampusModel::find($id);

        if (!$kampus) {
            return response()->json([
                'status' => false,
                'message' => 'Data kampus tidak ditemukan'
            ]);
        }

        return view('kampus.edit_ajax', ['kampus' => $kampus]);
    }

    // Update AJAX
   public function update_ajax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json([
                'status' => false,
                'message' => 'Permintaan tidak valid.'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'kampus_kode' => 'required|min:3|max:20',
            'kampus_nama' => 'required|min:3|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $kampus = KampusModel::find($id);

        if (!$kampus) {
            return response()->json([
                'status' => false,
                'message' => 'Data kampus tidak ditemukan.'
            ]);
        }

        try {
            $kampus->kampus_kode = $request->kampus_kode;
            $kampus->kampus_nama = $request->kampus_nama;
            $kampus->save();

            return response()->json([
                'status' => true,
                'message' => 'Data kampus berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data kampus.',
                'error' => $e->getMessage()
            ]);
        }
        return redirect('/');
    }

}