<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilUjianModel extends Model
{
    protected $table = 'hasil_ujian';
    protected $primaryKey = 'hasil_id';
    public $timestamps = true;

    protected $fillable = [
        'nilai_listening',
        'nilai_reading',
        'nilai_total',
        'status_lulus',
        'catatan',
        'jadwal_id',
        'user_id',

    ];

    public function jadwal()
    {
        return $this->belongsTo(JadwalModel::class, 'jadwal_id', 'jadwal_id');
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

}
