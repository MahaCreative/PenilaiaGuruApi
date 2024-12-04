<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilPenilaianKepsek extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }
}
