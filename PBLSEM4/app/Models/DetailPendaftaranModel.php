<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPendaftaranModel extends Model
{
    protected $table = 'detail_pendaftaran';
    protected $primaryKey = 'detail_id';
    public $timestamps = true;
    protected $guarded = [];
    protected $keyType = 'int'; // (kalau detail_id integer)
    public $incrementing = true; // (kalau auto-increment)


    protected $fillable = ['status', 'catatan'];

    public function pendaftaran()
    {
        return $this->belongsTo(PendaftaranModel::class, 'pendaftaran_id');
    }

}
