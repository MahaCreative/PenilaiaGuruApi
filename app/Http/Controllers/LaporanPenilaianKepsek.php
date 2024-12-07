<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianKepsek;
use Illuminate\Http\Request;

class LaporanPenilaianKepsek extends Controller
{
    public function index(Request $request, $id)
    {
        $data = DetailPenilaianKepsek::where('periode_id', $id)->get();
    }
}
