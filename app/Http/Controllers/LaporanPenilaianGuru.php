<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianKepsek;
use App\Models\DetailPenilaianSiswa;
use App\Models\HasilPenilaianKepsek;
use App\Models\HasilPenilaianSiswa;
use App\Models\NormalisasiPenilaianKepsek;
use App\Models\NormalisasiPenilaianSiswa;
use App\Models\PenilaianKepsek;
use App\Models\PenilaianSiswa;
use App\Models\Periode;
use App\Models\RankingTotal;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanPenilaianGuru extends Controller
{
    public function index(Request $request, $id)
    {

        $periode = Periode::findOrFail($id);
        $rankTotal = RankingTotal::with(['guru' => function ($q) {
            $q->select('nama', 'nip', 'id');
        }])->where('periode_id', $id)->get();
        $hasilRankSiswa = HasilPenilaianSiswa::with(['guru' => function ($q) {
            $q->select('nama', 'nip', 'id');
        }])->where('periode_id', $id)->orderBy('nilai_akhir', 'desc')->get();
        $hasilRanKepsek = HasilPenilaianKepsek::with(['guru' => function ($q) {
            $q->select('nama', 'nip', 'id');
        }])->where('periode_id', $id)->orderBy('nilai_akhir', 'desc')->get();
        $normalisasiSiswa = NormalisasiPenilaianSiswa::with('detail_penilaian_siswa', 'kriteria', 'guru')->where('periode_id', $id)->get();
        $normalisasiKepsek = NormalisasiPenilaianKepsek::with('detail_penilaian_kepsek', 'kriteria', 'guru')->where('periode_id', $id)->get();



        $pdf = Pdf::loadView('Report.ReportAll', compact(
            'periode',
            'rankTotal',
            'hasilRankSiswa',
            'hasilRanKepsek',
            'normalisasiKepsek',
            'normalisasiSiswa',

        )); // Load tampilan yang ingin dicetak
        $pdf->setPaper('legal', 'landscape'); // Set ukuran kertas dan orientasi

        return $pdf->stream("laporan-penilaian-guru-periosde-$periode->bulan-$periode->tahun.pdf");
    }
}
