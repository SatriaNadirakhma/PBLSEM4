<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'mahasiswa_id';
    public $timestamps = true;

    protected $fillable = [
        'nim', 'nik', 'mahasiswa_nama', 'angkatan',
        'no_telp', 'alamat_asal', 'alamat_sekarang',
        'jenis_kelamin', 'status', 'keterangan', 'prodi_id'
    ];
}
