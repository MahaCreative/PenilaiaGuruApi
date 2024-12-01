<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DataKelasController extends Controller
{
    public function index(Request $request)
    {
        $dataKelas = Kelas::withCount(['siswa as total', 'siswa as perempuan' => function ($q) {
            $q->where('jenis_kelamin', 'perempuan');
        }, 'siswa' => function ($q) {
            $q->where('jenis_kelamin', 'Laki-laki');
        }])->latest()->get();
        $data = [
            'dataKelas' => $dataKelas,
            'count' => $dataKelas->sum('total')
        ];
        return response()->json($data);
    }

    public function show_siswa(Request $request, $kode_kelas)
    {
        $kelas = Kelas::where('kode_kelas', $kode_kelas)->first();

        $quer = Siswa::query()->where('kelas_id', '=', $kelas->id);
        if ($request->jenis_kelamin) {
            $quer->where('jenis_kelamin', '=', $request->jenis_kelamin);
        }
        $siswa = $quer->latest()->get();
        return response()->json($siswa);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|min:3|string|unique:kelas,nama_kelas'
        ]);
        $kelas = Kelas::create([
            'kode_kelas' => \Str::slug($request->nama_kelas),
            'nama_kelas' => $request->nama_kelas
        ]);
        return response()->json(['message' => 'Kelas berhasil ditambahkan']);
    }



    public function delete(Request $request, $kode_kelas)
    {
        $kelas = Kelas::where('kode_kelas', $kode_kelas)->first();
        $kelas->delete();
        return response()->json(['message' => 'Kelas berhasil dihapus']);
    }
}
