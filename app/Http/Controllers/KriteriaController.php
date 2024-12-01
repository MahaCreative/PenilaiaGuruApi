<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Kriteria::query();
        if ($request->search) {
            $query->where('type', '=', $request->search);
        }
        $count = [
            'total' => Kriteria::count(),
            'kepsek' => Kriteria::where('type', 'kepsek')->count(),
            'siswa' => Kriteria::where('type', 'siswa')->count(),
        ];
        $kriteria = $query->latest()->get();

        return response()->json(compact('kriteria', 'count'));
    }

    public function create(Request $request)
    {
        $attr = $request->validate([
            'nama_kriteria' => 'required|string',
            'bobot_kriteria' => 'required|numeric|integer|digits_between:1,3',
            'type' => 'required|in:kepsek,siswa',
        ]);
        // Proses normalisasi nilai dari bobot
        $kd_kriteria = 'kr' . Kriteria::count() + 1;
        $attr['kd_penilaian'] = $kd_kriteria;
        $kriteria = Kriteria::create($attr);

        $getKriteria = Kriteria::where('type', $request->type)->get();
        if (count($getKriteria) >= 1) {
            $totalBobot = $getKriteria->sum('bobot_kriteria');

            foreach ($getKriteria as $item) {
                $fuzzy =  $item->bobot_kriteria / $totalBobot;
                $item->fuzzy = number_format($fuzzy, 5);
                $item->save();
            }
            $kriteria['fuzzy'] = $kriteria['bobot_kriteria'] / $totalBobot;
            $kriteria->save();
        }
        return response()->json(['message' => 'Berhasil menambahkan 1 data kriteria baru']);
    }

    public function delete(Request $request, $id)
    {
        $kriteria = Kriteria::find($id);
        $kriteria->delete();
        return response()->json(['message' => 'Berhasil menghapus 1 data kriteria baru']);
    }
}
