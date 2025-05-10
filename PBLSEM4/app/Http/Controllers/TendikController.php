<?php

namespace App\Http\Controllers;
use App\Models\TendikModel;

use Illuminate\Http\Request;

class TendikController extends Controller
{
    public function index()
    {
        $data = TendikModel::all();
        return view('tendik.index', compact('data'));
    }
}
