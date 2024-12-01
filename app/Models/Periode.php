<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;
    protected $fillable = [
        'bulan',
        'tahun',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status',
    ];
}
