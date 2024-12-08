<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianKepsek;
use App\Models\Guru;
use App\Models\Kriteria;
use App\Models\PenilaianKepsek;
use App\Models\Periode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanPenilaianKepsek extends Controller
{
    public function history_penilaian(Request $request, $id)
    {
        $periode = Periode::findOrFail($id);
        $showData = $request->showData;

        if ($request->showData == 'guru') {

            $data = Guru::with([
                'detail_penilaian_kepsek' => function ($q) use ($request) {
                    $q->with(['penilaian_kepesek' => function ($q) {
                        $q->with('user');
                    }, 'kriteria']);
                }
            ])
                ->whereHas('detail_penilaian_kepsek.penilaian_kepesek', function ($q) use ($id) {
                    $q->where('periode_id', '=', $id);
                })
                ->get();
        }
        if ($request->showData == 'kriteria') {
            $data = Kriteria::with(['detail_penilaian_kepsek' => function ($q) use ($request) {
                $q->with(['penilaian_kepesek' => function ($q) {
                    $q->with('user');
                }, 'guru']);
            }])
                ->whereHas('detail_penilaian_kepsek.penilaian_kepesek', function ($q) use ($id) {
                    $q->where('periode_id', '=', $id);
                })->get();
        }
        // return $data;

        // $data = DetailPenilaianSiswa::where('periode_id', $id)->get();
        $imagePath = public_path('alchaeriyah.png');
        $pdf = Pdf::loadView('Report.HistoryPenilaianKepsek', compact('data', 'periode', 'imagePath', 'showData')); // Load tampilan yang ingin dicetak
        $pdf->setPaper('legal', 'landscape'); // Set ukuran kertas dan orientasi

        return $pdf->stream("laporan-penilaian-guru-periosde-$periode->bulan-$periode->tahun.pdf");
    }
}
