<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'dosen_id';
    public $timestamps = true;

    protected $fillable = [
        'nidn', 'nik', 'dosen_nama',
        'no_telp', 'alamat_asal', 'alamat_sekarang',
        'jenis_kelamin', 'jurusan_id'
    ];
}
