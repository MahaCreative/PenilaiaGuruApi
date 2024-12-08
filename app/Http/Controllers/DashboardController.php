<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Periode;
use App\Models\RankingTotal;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $count = [
            'siswa' => Siswa::count(),
            'kelas' => Kelas::count(),
            'periode' => Periode::count(),
            'guru' => Guru::count(),
        ];
        if ($request->periode_id) {
            $rank = RankingTotal::with('guru')
                ->where('periode_id', $request->periode_id)
                ->orderBy('skor_akhir', 'desc')->latest()->get();
        } else {
            $rank = RankingTotal::with('guru')->orderBy('skor_akhir', 'desc')->latest()->get();
        }
        return response()->json(compact('count', 'rank'));
    }
}
