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
        'nim',
        'nik',
        'mahasiswa_nama',
        'angkatan',
        'no_telp',
        'alamat_asal',
        'alamat_sekarang',
        'jenis_kelamin',
        'status',
        'keterangan',
        'prodi_id'
    ];

    public function user()
    {
        return $this->hasOne(UserModel::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function prodi() {
        return $this->belongsTo(ProdiModel::class, 'prodi_id');
    }

    public function jurusan() {
        return $this->belongsTo(JurusanModel::class, 'jurusan_id');
    }

    public function kampus() {
        return $this->belongsTo(KampusModel::class, 'kampus_id');
    }

}
