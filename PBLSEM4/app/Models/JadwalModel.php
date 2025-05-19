<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalModel extends Model
{
    protected $table = 'jadwal';
    protected $primaryKey = 'jadwal_id';
    public $timestamps = true;

    protected $fillable = [
        'tanggal_pelaksanaan',
        'jam_mulai',
        'keterangan',
    ];
}
