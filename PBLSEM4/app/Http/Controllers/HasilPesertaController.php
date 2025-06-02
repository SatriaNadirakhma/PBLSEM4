<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\HasilUjianModel;

class HasilPesertaController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Riwayat Hasil Ujian',
            'list' => ['Home', 'Hasil Ujian'],
        ];
        
        $page = (object) [
            'title' => 'Riwayat Hasil Ujian',
        ];
        
        $activeMenu = 'hasilPeserta';
        $userId = Auth::id(); // Ambil ID user yang sedang login
        
        // Ambil data hasil ujian dengan relasi jadwal
        $hasilPeserta = HasilUjianModel::with('jadwal') // Assuming ada relasi dengan tabel jadwal
            ->where('user_id', $userId)
            ->get();
        
        return view('hasilPeserta.index', compact('breadcrumb', 'page', 'activeMenu', 'hasilPeserta'));
    }
}