<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianKepsek;
use App\Models\DetailPenilaianSiswa;
use App\Models\HasilPenilaianKepsek;
use App\Models\PenilaianKepsek;
use App\Models\PenilaianSiswa;
use App\Models\RankingTotal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GuruHistory extends Controller
{
    public function history_penilaian_kepsek(Request $request, $id)
    {


        $detailPenilaian = DetailPenilaianKepsek::with(['penilaian_kepesek' => function ($q) {
            $q->with('periode');
        }, 'kriteria', 'guru'])
            ->whereHas('penilaian_kepesek', function ($q) use ($id) {
                $q->where('periode_id', '=', $id);
            })
            ->where('guru_id', '=', $request->user()->guru->id)
            ->latest()->get();
        return $detailPenilaian;
    }
    public function history_penilaian_siswa(Request $request, $id)
    {

        $penilaianSiswa = PenilaianSiswa::where('periode_id', $id)->first();

        if (!$penilaianSiswa) {
            throw ValidationException::withMessages(["message" => "Belum ada penilaian yang diberikan oleh siswa"]);
        }
        $query = DetailPenilaianSiswa::query()->with(['penilaian_siswa' => function ($q) {
            $q->with('periode');
        }, 'kriteria', 'guru', 'user'])
            ->where('guru_id', '=', $request->user()->guru->id)
            ->whereHas('penilaian_siswa', function ($q) use ($id) {
                $q->where('periode_id', '=', $id);
            });

        $detailPenilaian = $query->latest()->get();
        return $detailPenilaian;
    }

    public function ranking_kepsek(Request $request)
    {
        $rankingKepsek = RankingTotal::with('periode')->where('guru_id', $request->user()->guru->id)
            ->orderBy('periode_id', 'desc')->latest()->get();
        return $rankingKepsek;
    }
}
