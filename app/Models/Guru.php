<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;
    protected $fillable = [
        'nip',
        'nama',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'foto_profile',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail_penilaian_siswa()
    {
        return $this->hasMany(DetailPenilaianSiswa::class);
    }
}
