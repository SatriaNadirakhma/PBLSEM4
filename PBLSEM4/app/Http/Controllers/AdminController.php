<?php

namespace App\Http\Controllers;
use App\Models\AdminModel;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $data = AdminModel::all();
        return view('admin.index', compact('data'));
    }
}
