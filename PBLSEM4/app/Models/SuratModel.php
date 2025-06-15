<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SuratModel extends Model
{
    use HasFactory;

    protected $table = 'surats'; // Pastikan nama tabelnya benar
    protected $fillable = ['file_path', 'file_name', 'judul_surat']; // Tambahkan 'judul_surat'

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->url($this->file_path);
        }
        return null;
    }
}