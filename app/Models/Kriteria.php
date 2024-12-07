<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $fillable = [
        'kd_penilaian',
        'nama_kriteria',
        'bobot_kriteria',
        'type'
    ];

    public function detail_penilaian_siswa()
    {
        return $this->hasMany(DetailPenilaianSiswa::class);
    }
    public function detail_penilaian_kepsek()
    {
        return $this->hasMany(DetailPenilaianKepsek::class);
    }
}
