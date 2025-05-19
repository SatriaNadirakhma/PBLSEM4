<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPendaftaranModel extends Model
{
    protected $table = 'detail_pendaftaran';
    protected $primaryKey = 'detail_id';
    public $timestamps = true;
    protected $guarded = [];
}
