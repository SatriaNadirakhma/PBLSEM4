<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UjianModel extends Model
{
    protected $table = 'ujian';
    protected $primaryKey = 'ujian_id';
    public $timestamps = true;

    protected $fillable = [
        'ujian_kode',
        'jadwal_id',
        'pendaftaran_id',
    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalModel::class, 'jadwal_id');
    }

     public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranModel::class, 'pendaftaran_id');
    }
}
