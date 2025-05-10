<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdiModel extends Model
{
    protected $table = 'prodi';
    protected $primaryKey = 'prodi_id';
    public $timestamps = true;

    protected $fillable = [
        'prodi_kode',
        'prodi_nama',
        'jurusan_id',
    ];

    public function jurusan()
    {
        return $this->belongsTo(JurusanModel::class, 'jurusan_id');
    }
}
