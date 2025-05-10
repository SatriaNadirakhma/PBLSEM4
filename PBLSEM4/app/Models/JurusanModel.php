<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurusanModel extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'jurusan_id';
    public $timestamps = true;

    protected $fillable = [
        'jurusan_kode',
        'jurusan_nama',
        'kampus_id',
    ];

    public function kampus()
    {
        return $this->belongsTo(KampusModel::class, 'kampus_id');
    }

    public function prodi()
    {
        return $this->hasMany(ProdiModel::class, 'jurusan_id');
    }
}
