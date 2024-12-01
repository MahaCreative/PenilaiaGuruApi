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
}
