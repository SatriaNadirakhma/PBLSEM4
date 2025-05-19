<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalModel;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = JadwalModel::all();
        return view('jadwal.index', compact('jadwals'));
    }

    // public function create()
    // {
    //     return view('jadwal.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'tanggal_pelaksanaan' => 'required|date',
    //         'jam_mulai' => 'nullable|date_format:H:i:s',
    //         'keterangan' => 'nullable|string',
    //     ]);

    //     JadwalModel::create($request->all());
    //     return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    // }

    // public function edit($id)
    // {
    //     $jadwal = JadwalModel::findOrFail($id);
    //     return view('jadwal.edit', compact('jadwal'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'tanggal_pelaksanaan' => 'required|date',
    //         'jam_mulai' => 'nullable|date_format:H:i:s',
    //         'keterangan' => 'nullable|string',
    //     ]);

    //     $jadwal = JadwalModel::findOrFail($id);
    //     $jadwal->update($request->all());
    //     return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    // }

    // public function destroy($id)
    // {
    //     $jadwal = JadwalModel::findOrFail($id);
    //     $jadwal->delete();
    //     return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    // }
}
