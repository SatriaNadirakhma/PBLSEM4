<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataDiriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Diri',
            'list' => ['Home', 'Data Diri'],
        ];

        $page = (object) [
            'title' => 'Informasi Data Diri Mahasiswa',
        ];

        $activeMenu = 'datadiri';

        return view('datadiri.index', compact('breadcrumb', 'page', 'activeMenu'));
    }
}

