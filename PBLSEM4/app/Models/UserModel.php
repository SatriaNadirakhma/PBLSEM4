<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute; 

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'user_id';
    public $timestamps = true;

    protected $fillable = [
        'email', 'username', 'password', 'profile', 'role',
        'admin_id', 'mahasiswa_id', 'dosen_id', 'tendik_id'
    ];

    // Relasi 
    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'admin_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(MahasiswaModel::class, 'mahasiswa_id');
    }

    public function dosen()
    {
        return $this->belongsTo(DosenModel::class, 'dosen_id');
    }

    public function tendik()
    {
        return $this->belongsTo(TendikModel::class, 'tendik_id');
    }


    public function getNamaLengkapAttribute()
    {
        switch ($this->role) {
            case 'admin':
                return $this->admin->admin_nama ?? '-';
            case 'mahasiswa':
                return $this->mahasiswa->mahasiswa_nama ?? '-';
            case 'dosen':
                return $this->dosen->dosen_nama ?? '-';
            case 'tendik':
                return $this->tendik->tendik_nama ?? '-';
            default:
                return '-';
        }
    }

    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture 
            ? asset('storage/profile/' . basename($this->profile_picture))
            : asset('img/default-profile.png');
    }

    protected function image(): Attribute 
    { 
        return Attribute::make( 
            get: fn ($image) => url('/storage/posts/' . $image),
        );
    }

    public function hasil_ujian()
{
    return $this->hasMany(HasilUjianModel::class, 'user_id');
}


}
