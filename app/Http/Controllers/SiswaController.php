<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::query()->with('kelas');
        if ($request->kelas) {
            $query->whereHas('kelas', function ($q) use ($request) {
                $q->where('nama_kelas', '=', $request->nama_kelas);
            });
        }
        if ($request->nama) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }
        if ($request->nis) {
            $query->where('nis', $request->nis);
        }
        $siswa = $query->latest()->get();

        $count = [
            'total' => Siswa::count(),
            'perempuan' => Siswa::where('jenis_kelamin', 'perempuan')->count(),
            'laki' => Siswa::where('jenis_kelamin', 'laki-  laki')->count(),
        ];
        return response()->json(['siswa' => $siswa, 'count' => $count]);
    }

    public function show(Request $request, $nis)
    {
        $siswa = Siswa::where('nis', $nis)->with('kelas')->first();
        return response()->json($siswa);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nis' => 'required|numeric|digits:6|unique:siswas,nis',
            'nama' => 'required|string|min:4|max:60',
            'alamat' => 'required|min:6|max:50|string',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date|before:now',
            'no_hp' => 'required|digits:12|numeric',
            'foto_profile' => 'required|image|mimes:png,jpeg,jpg',
            'password' => 'required|alpha_dash|min:6',
            'kelas' => 'required',
        ]);
        $kelas = Kelas::where('kode_kelas', $request->kelas)->first();
        $foto_profile = $request->file('foto_profile')->store('FotoProfile/Siswa');

        $user = User::create([
            'nip' => $request->nis,
            'name'  => $request->nama,
            'password' => bcrypt($request->password),
        ]);
        $siswa = Siswa::create([
            'kelas_id' => $kelas->id,
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'foto_profile' => $foto_profile,
        ]);
    }

    public function delete(Request $request, $nis)
    {
        $siswa = Siswa::where('nis', $nis)->first();
        $siswa->delete();
    }
}
