<?php

namespace App\Http\Controllers;
use App\Models\UserModel;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $data = UserModel::all();
        return view('user.index', compact('data'));
    }

    public function admin()
    {
        return view('user.admin', ['activeMenu' => 'user-admin']);
    }

    public function mahasiswa()
    {
        return view('user.mahasiswa', ['activeMenu' => 'user-mahasiswa']);
    }

    public function dosen()
    {
        return view('user.dosen', ['activeMenu' => 'user-dosen']);
    }

    public function tendik()
    {
        return view('user.tendik', ['activeMenu' => 'user-tendik']);
    }
}
