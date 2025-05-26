<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranModel extends Model
{
    protected $table = 'pendaftaran';
    protected $primaryKey = 'pendaftaran_id';
    public $timestamps = true;

    protected $fillable = [
        'pendaftaran_kode',
        'tanggal_pendaftaran',
        'scan_ktp',
        'scan_ktm',
        'pas_foto',
        'mahasiswa_id',
        'jadwal_id',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(JadwalModel::class, 'jadwal_id');
    }

    public function detail()
    {
        return $this->hasOne(DetailPendaftaranModel::class, 'pendaftaran_id');
    }

    public function detail_terakhir()
    {
        return $this->hasOne(DetailPendaftaranModel::class, 'pendaftaran_id')->latestOfMany('created_at');
    }


}
