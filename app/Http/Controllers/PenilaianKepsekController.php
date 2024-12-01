<?php

namespace App\Http\Controllers;

use App\Models\DetailPenilaianKepsek;
use App\Models\Guru;
use App\Models\Kriteria;
use App\Models\PenilaianKepsek;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PenilaianKepsekController extends Controller
{
    public function index(Request $request)
    {
        $getPeriode = Periode::latest()->first();

        if (!$getPeriode) {
            throw ValidationException::withMessages([
                'message' => ' Penilaian kinerja guru belum bisa di tambahkan, karena data periode mungkin belum ditambahkan atau semuanya sudah selesai'
            ]);
        }
        // dd($getPeriode->id);
        $cekPenilaian = PenilaianKepsek::with('periode')->where('periode_id', '=', $getPeriode->id,)
            ->where('user_id', $request->user()->id)->first();
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
        if (count($getGuru) == 0) {
            throw ValidationException::withMessages(['message' => 'Anda sudah memberikan penilaian ke semua guru di periode ini']);
        }
        return response()->json(compact('getGuru', 'cekPenilaian'));
    }

    public function create(Request $request, $id)
    {
        $request->validate([

            'id_kriteria.*' => 'required|numeric',
            'guru_id' => 'required|numeric',
            'nilai.*' => 'required|numeric|min:1|max:5'
        ]);
        for ($i = 0; $i < count($request->id_kriteria); $i++) {
            $detailPenilaian = DetailPenilaianKepsek::create([
                'user_id' => $request->user()->id,
                'penilaian_kepsek_id' => $id,
                'kriteria_id' => $request->id_kriteria[$i],
                'guru_id' => $request->guru_id,
                'nilai' => $request->nilai[$i],
                'tanggal_penilaian' => now(),
            ]);
        }
        return response()->json(['message' => 'Berhasil menambahkan penilaian']);
    }
}
