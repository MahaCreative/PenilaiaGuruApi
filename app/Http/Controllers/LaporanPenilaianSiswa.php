<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianSiswa;
use App\Models\Guru;
use App\Models\Kriteria;
use App\Models\PenilaianSiswa;
use App\Models\Periode;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class LaporanPenilaianSiswa extends Controller
{
    public function history_penilaian(Request $request, $id)
    {
        $periode = Periode::findOrFail($id);
        $showData = $request->showData;
        if ($request->showData == 'siswa') {
            $data = PenilaianSiswa::with([
                'user' => function ($q) {
                    $q->with([
                        'siswa' => function ($q) {
                            $q->with('kelas');
                        }
                    ]);
                },
                'detail_penilaian_siswa' => function ($q) use ($request) {
                    $q->with('guru', 'kriteria');
                }
            ])->where('periode_id', $id)->latest()->get();
        }
        if ($request->showData == 'guru') {
            $data = Guru::with([
                'detail_penilaian_siswa' => function ($q) use ($request) {
                    if ($request->orderBy == 'kriteria_id') {
                        $q->with('user', 'kriteria');
                    }
                }
            ])->get();
        }
        if ($request->showData == 'kriteria') {
            $data = Kriteria::with(['detail_penilaian_siswa' => function ($q) use ($request) {

                $q->with('user', 'guru');
            }])->get();
        }
        // return $data;

        // $data = DetailPenilaianSiswa::where('periode_id', $id)->get();
        $imagePath = public_path('alchaeriyah.png');
        $pdf = Pdf::loadView('Report.HistoryPenilaianSiswa', compact('data', 'periode', 'imagePath', 'showData')); // Load tampilan yang ingin dicetak
        $pdf->setPaper('legal', 'landscape'); // Set ukuran kertas dan orientasi

        return $pdf->stream('invoice.pdf');
    }
}
