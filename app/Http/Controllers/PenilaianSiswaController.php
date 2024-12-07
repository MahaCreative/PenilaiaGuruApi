<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianSiswa;
use App\Models\Guru;
use App\Models\HasilPenilaianSiswa;
use App\Models\Kriteria;
use App\Models\NormalisasiPenilaianSiswa;
use App\Models\PenilaianSiswa;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PenilaianSiswaController extends Controller
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
        $cekPenilaian = PenilaianSiswa::with('periode')->where('periode_id', '=', $getPeriode->id,)
            ->where('user_id', $request->user()->id)

            ->first();
        if (!$cekPenilaian) {
            $cekPenilaian = PenilaianSiswa::create([
                "periode_id" => $getPeriode->id,
                "user_id" => $request->user()->id,

            ]);
            $idPenilaian = $cekPenilaian->id;
        } else {
            $idPenilaian = $cekPenilaian->id;
        }
        $penilaian = DetailPenilaianSiswa::where('penilaian_siswa_id', '=', $idPenilaian)
            ->get()->pluck('guru_id');

        $getGuru = Guru::whereNotIn('id', $penilaian)->get();

        return response()->json(compact('getGuru', 'cekPenilaian'));
    }

    public function store_nilai(Request $request, $id)
    {
        $request->validate([

            'id_kriteria.*' => 'required|numeric',
            'guru_id' => 'required|numeric',
            'nilai.*' => 'required|numeric|min:1|max:5'
        ]);
        for ($i = 0; $i < count($request->id_kriteria); $i++) {
            $detailPenilaian = DetailPenilaianSiswa::create([
                'user_id' => $request->user()->id,
                'penilaian_siswa_id' => $id,
                'kriteria_id' => $request->id_kriteria[$i],
                'guru_id' => $request->guru_id,
                'nilai' => $request->nilai[$i],
                'tanggal_penilaian' => now(),
            ]);
        }
        $penilaianSiswa = PenilaianSiswa::findOrFail($id);
        $penilaianSiswa->update(['jumlah_guru_dinilain' => $penilaianSiswa->jumlah_guru_dinilain + 1]);
        return response()->json(['message' => 'Berhasil menambahkan penilaian']);
    }

    public function history_penilaian(Request $request, $id)
    {
        $penilaianSiswa = PenilaianSiswa::where('periode_id', $id)
            ->where('user_id', $request->user()->id)
            ->first();
        $detailPenilaian = DetailPenilaianSiswa::with(['penilaian_siswa' => function ($q) {
            $q->with('periode');
        }, 'kriteria', 'guru'])->where('penilaian_siswa_id', '=', $penilaianSiswa->id)
            ->where('user_id', '=', $request->user()->id)
            ->latest()->get();
        return $detailPenilaian;
    }

    public function proses_penilaian(Request $request, $id)
    {
        $getPenilaianSiswa = PenilaianSiswa::where('periode_id', $id)->get()->pluck('id');

        // Ambil semua kriteria untuk tipe 'siswa'
        $getKriteria = Kriteria::where('type', '=', 'siswa')->get();

        $data = []; // Array untuk menyimpan data normalisasi
        DB::beginTransaction();
        try {
            NormalisasiPenilaianSiswa::where('periode_id', $id)->delete();
            HasilPenilaianSiswa::where('periode_id', $id)->delete();
            // Loop melalui setiap kriteria
            foreach ($getKriteria as $kriteria) {
                // Ambil semua detail penilaian siswa untuk kriteria ini
                $penilaianSiswaDetails = DetailPenilaianSiswa::whereIn('penilaian_siswa_id', $getPenilaianSiswa)
                    ->where('kriteria_id', $kriteria->id)
                    ->get();

                // Cari nilai maksimum untuk kriteria ini
                $nilaiMaksimum = $penilaianSiswaDetails->max('nilai');

                // Periksa jika nilai maksimum lebih besar dari 0 untuk menghindari pembagian dengan 0
                if ($nilaiMaksimum > 0) {
                    // Loop melalui setiap detail penilaian siswa untuk menghitung normalisasi
                    foreach ($penilaianSiswaDetails as $detail) {
                        $normalisasi = ($detail->nilai / $nilaiMaksimum) * $detail->kriteria->fuzzy;

                        // Simpan hasil normalisasi ke dalam array data
                        $data[] = [
                            'detail_penilaian_siswa_id' => $detail->id,
                            'periode_id' => $detail->periode_id,
                            'kriteria_id' => $kriteria->id,
                            'guru_id' => $detail->guru_id,
                            'normalisasi' => $normalisasi,
                        ];


                        NormalisasiPenilaianSiswa::create([
                            'user_id' => $detail->user_id,
                            'detail_penilaian_siswa_id' => $detail->id,
                            'periode_id' => $id,
                            'kriteria_id' => $kriteria->id,
                            'guru_id' => $detail->guru_id,
                            'normalisasi' => $normalisasi,
                        ]);
                    }
                }
            }
            DB::commit();
            return $this->hitungSkorAkhir($id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function hitungSkorAkhir($id)
    {


        // Mengambil jumlah total user yang seharusnya menilai semua guru
        $totalUser = DB::table('penilaian_siswas')
            ->select(DB::raw('COUNT(DISTINCT user_id) as total_user'))
            ->where('periode_id', $id)
            ->first()
            ->total_user;

        // Menghitung hasil rata-rata normalisasi
        $result = DB::table('normalisasi_penilaian_siswas')
            ->select(
                'guru_id',
                DB::raw('SUM(normalisasi) as total_normalisasi'),
                DB::raw('COUNT(DISTINCT user_id) as total_user_per_guru')
            )
            ->whereBetween('kriteria_id', [1, 5])
            ->groupBy('guru_id')
            ->orderBy('guru_id')
            ->get();

        // Menampilkan hasil dengan rata-rata normalisasi untuk setiap guru_id
        foreach ($result as $item) {
            $average_normalisasi = $item->total_normalisasi / $totalUser;
            $data = HasilPenilaianSiswa::create([
                'periode_id' => $id,
                'guru_id' => $item->guru_id,
                'nilai_akhir' => $average_normalisasi,
                'jumlah_siswa_menilai' => $item->total_user_per_guru,
            ]);
            // Menghitung rata-rata normalisasi dengan total user yang seharusnya menilai semua guru
            echo $data;
        }
        // Array untuk menyimpan total nilai per guru dan jumlah siswa yang menilai per guru

    }
    public function rangking_sementara(Request $request)
    {


        $normalisasi = NormalisasiPenilaianSiswa::with('guru', 'kriteria')->where('periode_id', $request->periode_id)
            ->latest()->get();
        $hasilAkhir = HasilPenilaianSiswa::with('guru')->where('periode_id', $request->periode_id)
            ->orderBy('nilai_akhir', 'desc')
            ->latest()->get();
        return response()->json(['normalisasi' => $normalisasi, 'hasilAkhir' => $hasilAkhir]);
    }
}
