<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformasiModel extends Model
{
    protected $table = 'informasi';
    protected $primaryKey = 'informasi_id';
    public $timestamps = true;

    protected $fillable = [
        'judul',
        'isi',
    ];
}
