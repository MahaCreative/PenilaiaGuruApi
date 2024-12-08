<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenilaianSiswa extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function penilaian_siswa()
    {
        return $this->belongsTo(PenilaianSiswa::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
