<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KampusModel;

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

    public function kampus()
    {
        return $this->belongsTo(KampusModel::class, 'kampus_id');
    }

    public function user()
    {
        return $this->hasOne(UserModel::class, 'tendik_id', 'tendik_id');
    }
}
