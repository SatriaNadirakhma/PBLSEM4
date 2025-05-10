<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TendikModel extends Model
{
    use HasFactory;

    protected $table = 'tendik';
    protected $primaryKey = 'tendik_id';
    public $timestamps = true;

    protected $fillable = [
        'nip', 'nik', 'tendik_nama',
        'no_telp', 'alamat_asal', 'alamat_sekarang',
        'jenis_kelamin', 'kampus_id'
    ];
}
