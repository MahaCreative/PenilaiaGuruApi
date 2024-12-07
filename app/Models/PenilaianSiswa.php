<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianSiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail_penilaian_siswa()
    {
        return $this->hasMany(DetailPenilaianSiswa::class);
    }
}
