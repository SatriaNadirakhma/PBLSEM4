<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class KampusModel extends Model
{
    use HasFactory;

    protected $table = 'kampus';
    protected $primaryKey = 'kampus_id';
    public $timestamps = true;

    protected $fillable = [
        'kampus_kode',
        'kampus_nama',
    ];

    public function jurusan()
    {
        return $this->hasMany(JurusanModel::class, 'kampus_id');
    }
}
