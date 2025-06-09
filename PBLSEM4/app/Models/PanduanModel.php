<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // <<< PASTIKAN INI ADA

class PanduanModel extends Model
{
    use HasFactory;

    protected $table = 'panduans'; // Pastikan nama tabelnya benar
    protected $fillable = ['file_path', 'file_name'];

    // <<< TAMBAHKAN BAGIAN INI >>>
    protected $appends = ['file_url']; // Ini penting agar file_url otomatis ditambahkan saat model di-load

    public function getFileUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }
    // <<< AKHIR BAGIAN YANG DITAMBAHKAN >>>
}