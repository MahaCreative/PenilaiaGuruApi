<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenilaianKepsek extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function penilaian_kepesek()
    {
        return $this->belongsTo(PenilaianKepsek::class,  'penilaian_kepsek_id');
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
