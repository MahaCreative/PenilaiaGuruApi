<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKepsek extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
