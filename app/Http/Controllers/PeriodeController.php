<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index(Request $request)
    {
        $query = Periode::query();
        $periode = $query->latest()->get();
        $count = [
            'total' => Periode::count(),
            'tahun' => Periode::where('tahun', now()->format('Y'))->count(),
        ];
        return response()->json([
            'count' => $count,
            'periode' => $periode,
            'tahun_ini' => now()->format('Y')
        ]);
    }

    public function show(Request $request) {}

    public function create(Request $request)
    {
        $cekPeriode = Periode::where('bulan', $request->bulan)
            ->where('tahun', $request->tahun)->first();
        if ($cekPeriode) {
            return response()->json([
                'message' => 'Periode Telah ditambahkan sebelumnya',

            ]);
        }
        $attr = $request->validate([
            'tahun' => 'required|integer|min:' . now()->format('Y'),
            'bulan' => 'required|integer|min:1|max:12',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after:' . Carbon::parse($request->tanggal_mulai)  . '|before:' .  Carbon::parse($request->tanggal_mulai)->endOfMonth(),
        ]);
        $periode = Periode::create($attr);
        return response()->json('Berhasil menambahkan data periode');
    }

    public function delete(Request $request, $id)
    {
        $periode = Periode::find($id);
        $periode->delete();
        return response()->json('Berhasil menghapus data periode');
    }
}
