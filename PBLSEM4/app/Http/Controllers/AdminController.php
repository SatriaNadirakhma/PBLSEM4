<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Admin',
            'list' => ['Home', 'Admin'],
        ];

        $page = (object) [
            'title' => 'Daftar admin yang terdaftar dalam sistem',
        ];

        $activeMenu = 'admin';

        return view('admin.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $admin = AdminModel::select('admin_id', 'admin_nama', 'no_telp');

        if ($request->has('search_query') && $request->search_query != '') {
            $admin->where('admin_nama', 'like', '%' . $request->search_query . '%');
    }

        return DataTables::of($admin)
            ->addIndexColumn() // Menambahkan kolom index
            ->addColumn('aksi', function ($k) {
                return '
                    <button onclick="modalAction(\'' . url('/admin/' . $k->admin_id . '/show_ajax') . '\')" class="btn btn-info btn-sm me-1">Detail</button>
                    <button onclick="modalAction(\'' . url('/admin/' . $k->admin_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm me-1">Edit</button>
                    <button onclick="modalAction(\'' . url('/admin/' . $k->admin_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>
                ';
            })
        ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi adalah HTML
        ->make(true);
    }

    public function show_ajax(string $id)
    {
        $admin = AdminModel::find($id);
        return view('admin.show_ajax', ['admin' => $admin]);
    }

    // Tambah Data AJAX
    public function create_ajax()
    {
        return view('admin.create_ajax');
    }
}
