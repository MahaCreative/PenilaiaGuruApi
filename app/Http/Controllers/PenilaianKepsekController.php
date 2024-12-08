<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianKepsek;
use App\Models\DetailPenilaianSiswa;
use App\Models\Guru;
use App\Models\HasilPenilaianKepsek;
use App\Models\HasilPenilaianSiswa;
use App\Models\Kriteria;
use App\Models\NormalisasiPenilaianKepsek;
use App\Models\PenilaianKepsek;
use App\Models\PenilaianSiswa;
use App\Models\Periode;
use App\Models\RankingTotal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenilaianKepsekController extends Controller
{
    public function index(Request $request, $id)
    {
        $getPeriode = Periode::where('status', '!=', 'selesai')
            ->where('id', $id)
            ->latest()->first();

        if (!$getPeriode) {
            throw ValidationException::withMessages([
                'message' => ' Penilaian kinerja guru belum bisa di tambahkan, karena data periode mungkin belum ditambahkan atau semuanya sudah selesai'
            ]);
        }

        $cekPenilaian = PenilaianKepsek::with('periode')->where('periode_id', '=', $getPeriode->id,)
            ->where('user_id', $request->user()->id)
            // ->where('user_id', 1)
            ->first();
        // $idPenilaian

        if (!$cekPenilaian) {
            $cekPenilaian = PenilaianKepsek::create([
                "periode_id" => $getPeriode->id,
                "user_id" => $request->user()->id,

            ]);
            $idPenilaian = $cekPenilaian->id;
        } else {
            $idPenilaian = $cekPenilaian->id;
        }
        // cek isian guru
        $penilaian = DetailPenilaianKepsek::where('penilaian_kepsek_id', '=', $idPenilaian)->get()->pluck('guru_id');
        $getGuru = Guru::whereNotIn('id', $penilaian)->get();

        return response()->json(compact('getGuru', 'cekPenilaian'));
    }

    public function create(Request $request, $id)
    {
        // if()


        $request->validate([

            'id_kriteria.*' => 'required|numeric',
            'guru_id' => 'required|numeric',
            'nilai.*' => 'required|numeric|min:1|max:5'
        ]);
        $getPenilaianKepsek = PenilaianKepsek::where('user_id', $request->user()->id)
            ->where('periode_id', $id)
            ->first();
        for ($i = 0; $i < count($request->id_kriteria); $i++) {
            $detailPenilaian = DetailPenilaianKepsek::create([
                'user_id' => $request->user()->id,
                'penilaian_kepsek_id' => $getPenilaianKepsek->id,
                'kriteria_id' => $request->id_kriteria[$i],
                'guru_id' => $request->guru_id,
                'nilai' => $request->nilai[$i],
                'tanggal_penilaian' => now(),
            ]);
        }
        $penilaianKepsek = PenilaianKepsek::findOrFail($id);
        $penilaianKepsek->update(['jumlah_guru_dinilain' => $penilaianKepsek->jumlah_guru_dinilain + 1]);
        return response()->json(['message' => 'Berhasil menambahkan penilaian']);
    }

    public function proses_penilaian(Request $request, $id)
    {
        $getPenilaianKepsek = PenilaianKepsek::where('periode_id', $id)->first();
        $getKriteria = Kriteria::where('type', '=', 'kepsek')->get();

        DB::beginTransaction();
        try {
            // Hapus data normalisasi dan hasil sebelumnya
            NormalisasiPenilaianKepsek::where('periode_id', $id)->delete();
            HasilPenilaianKepsek::where('periode_id', $id)->delete();

            // Proses normalisasi
            foreach ($getKriteria as $itemKriteria) {
                $detailPenilaian = DetailPenilaianKepsek::where('user_id', $request->user()->id)
                    ->where('kriteria_id', $itemKriteria->id)
                    ->where('penilaian_kepsek_id', $getPenilaianKepsek->id)
                    ->get();
                $nilaiMax = $detailPenilaian->max('nilai');

                foreach ($detailPenilaian as $item) {
                    NormalisasiPenilaianKepsek::create([
                        'detail_penilaian_kepsek_id' => $item->id,
                        'periode_id' => $id,
                        'kriteria_id' => $item->kriteria_id,
                        'guru_id' => $item->guru_id,
                        'normalisasi' => $item->nilai / $nilaiMax,
                    ]);
                }
            }

            // Hitung nilai akhir
            $normalisasiData = NormalisasiPenilaianKepsek::where('periode_id', $id)->get();
            $bobotKriteria = Kriteria::where('type', 'kepsek')->pluck('fuzzy', 'id');
            $groupedByGuru = $normalisasiData->groupBy('guru_id');
            $hasilAkhir = [];

            foreach ($groupedByGuru as $guruId => $normalisasiItems) {
                $nilaiPreferensi = 0;

                foreach ($normalisasiItems as $item) {
                    $bobot = $bobotKriteria[$item->kriteria_id] ?? 0;
                    $nilaiPreferensi += $item->normalisasi * $bobot;
                }

                $hasilAkhir[] = [
                    'guru_id' => $guruId,
                    'nilai_preferensi' => $nilaiPreferensi,
                ];
            }

            foreach ($hasilAkhir as $hasil) {
                HasilPenilaianKepsek::create([
                    'periode_id' => $id,
                    'guru_id' => $hasil['guru_id'],
                    'nilai_akhir' => $hasil['nilai_preferensi'],
                ]);
            }


            $guru = Guru::latest()->get();
            $nilaiKepsek = 0;
            $nilaiSiswa = 0;
            $nilaiAkhir = 0;
            foreach ($guru as $item) {
                $cekHasilKepsek = HasilPenilaianKepsek::where(
                    'periode_id',
                    $id
                )
                    ->where('guru_id', $item->id)->first();
                $cekHasilSiswa = HasilPenilaianSiswa::where('periode_id', $id)
                    ->where('guru_id', $item->id)->first();
                if ($cekHasilKepsek) {
                    $nilaiKepsek = $cekHasilKepsek->nilai_akhir;
                } else {
                    $nilaiKepsek = 0;
                }
                if ($cekHasilSiswa) {
                    $nilaiSiswa = $cekHasilSiswa->nilai_akhir;
                } else {
                    $nilaiSiswa = 0;
                }
                $nilaiAkhir = ($nilaiKepsek + $nilaiSiswa);
                RankingTotal::updateOrCreate(['periode_id' => $id, 'guru_id' => $item->id,], [
                    'rank' => '1',

                    'nilai_kepsek' => $nilaiKepsek,
                    'nilai_siswa' => $nilaiSiswa,
                    'skor_akhir' => $nilaiAkhir,
                ]);
            }

            $top3Results = RankingTotal::with('guru')
                ->where('periode_id', '=', $id)
                ->orderBy('skor_akhir', 'desc')
                ->take(3)
                ->get();

            foreach ($top3Results as $rank => $item) {
                $item->rank = $rank + 1;
                $item->save();
            }
            $periode = Periode::where('id', '=', $id)->first();

            if ($periode && $top3Results->count() > 0) {

                $periode->rangking_1 = $top3Results[0]->guru->nip ?? null;
                $periode->skor_1 = $top3Results[0]->skor_akhir ?? null;
                $periode->rangking_2 = $top3Results[1]->guru->nip ?? null;
                $periode->skor_2 = $top3Results[1]->skor_akhir ?? null;
                $periode->rangking_3 = $top3Results[2]->guru->nip ?? null;
                $periode->skor_3 = $top3Results[2]->skor_akhir ?? null;
                $periode->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function history_penilaian(Request $request, $id)
    {
        $penilaianKepsek = PenilaianKepsek::where('periode_id', $id)->first();
        $detailPenilaian = DetailPenilaianKepsek::with(['penilaian_kepesek' => function ($q) {
            $q->with('periode');
        }, 'kriteria', 'guru'])->where('penilaian_kepsek_id', '=', $penilaianKepsek->id)
            ->where('user_id', '=', $request->user()->id)
            ->latest()->get();
        return $detailPenilaian;
    }

    public function rangking_sementara(Request $request)
    {


        $normalisasi = NormalisasiPenilaianKepsek::with('guru', 'kriteria')->where('periode_id', $request->periode_id)
            ->latest()->get();
        $hasilAkhir = HasilPenilaianKepsek::with('guru')->where('periode_id', $request->periode_id)
            ->orderBy('nilai_akhir', 'desc')
            ->latest()->get();
        return response()->json(['normalisasi' => $normalisasi, 'hasilAkhir' => $hasilAkhir]);
    }

    public function history_penilaian_siswa(Request $request, $id)
    {
        $penilaianSiswa = PenilaianSiswa::where('periode_id', $id)->first();
        if (!$penilaianSiswa) {
            throw ValidationException::withMessages(["message" => "Belum ada penilaian yang diberikan oleh siswa"]);
        }
        $query = DetailPenilaianSiswa::query()->with(['penilaian_siswa' => function ($q) {
            $q->with('periode');
        }, 'kriteria', 'guru', 'user'])->where('penilaian_siswa_id', '=', $penilaianSiswa->id);
        if ($request->nama_siswa) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama_siswa . '%');
            });
        }
        if ($request->nama_guru) {
            $query->whereHas('guru', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->nama_guru . '%');
            });
        }
        $detailPenilaian = $query->latest()->get();
        return $detailPenilaian;
    }

    public function selesaikan_penilaian(Request $request, $id)
    {
        $periode = Periode::find($id);
        $periode->status = 'selesai';
        $periode->save();
        $guru = Guru::latest()->get();
        $nilaiKepsek = 0;
        $nilaiSiswa = 0;
        $nilaiAkhir = 0;
        foreach ($guru as $item) {
            $cekHasilKepsek = HasilPenilaianKepsek::where(
                'periode_id',
                $id
            )
                ->where('guru_id', $item->id)->first();
            $cekHasilSiswa = HasilPenilaianSiswa::where('periode_id', $id)
                ->where('guru_id', $item->id)->first();
            if ($cekHasilKepsek) {
                $nilaiKepsek = $cekHasilKepsek->nilai_akhir;
            } else {
                $nilaiKepsek = 0;
            }
            if ($cekHasilSiswa) {
                $nilaiSiswa = $cekHasilSiswa->nilai_akhir;
            } else {
                $nilaiSiswa = 0;
            }
            $nilaiAkhir = ($nilaiKepsek + $nilaiSiswa);
            RankingTotal::updateOrCreate(['periode_id' => $id, 'guru_id' => $item->id,], [
                'rank' => '1',

                'nilai_kepsek' => $nilaiKepsek,
                'nilai_siswa' => $nilaiSiswa,
                'skor_akhir' => $nilaiAkhir,
            ]);
        }

        $top3Results = RankingTotal::with('guru')
            ->where('periode_id', '=', $id)
            ->orderBy('skor_akhir', 'desc')
            ->take(3)
            ->get();
        foreach ($top3Results as $rank => $item) {
            $item->rank = $rank + 1;
            $item->save();
        }


        if ($periode && $top3Results->count() > 0) {

            $periode->rangking_1 = $top3Results[0]->guru->nip ?? null;
            $periode->skor_1 = $top3Results[0]->skor_akhir ?? null;
            $periode->rangking_2 = $top3Results[1]->guru->nip ?? null;
            $periode->skor_2 = $top3Results[1]->skor_akhir ?? null;
            $periode->rangking_3 = $top3Results[2]->guru->nip ?? null;
            $periode->skor_3 = $top3Results[2]->skor_akhir ?? null;
            $periode->save();
        }
    }
}
