<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormalisasiPenilaianSiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
    public function detail_penilaian_siswa()
    {
        return $this->belongsTo(DetailPenilaianSiswa::class);
    }
}
