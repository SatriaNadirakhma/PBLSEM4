<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DosenModel;

class DosenController extends Controller
{
    public function index()
    {
        $data = DosenModel::all();
        return view('dosen.index', compact('data'));
    }
}
