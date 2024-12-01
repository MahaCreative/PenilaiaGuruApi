<?php

namespace App\Http\Controllers;

use App\Http\Resources\GuruResource;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {

        $query = Guru::query();
        if ($request->cari) {
            $query->where('nama', 'like', '%' . $request->cari . '%')->orWhere('nip', 'like', '%' . $request->cari . '%');
        }
        $guru = $query->latest()->get();
        $count = [
            'total' => Guru::count(),
            'perempuan' => Guru::where('jenis_kelamin', 'perempuan')->count(),
            'laki' => Guru::where('jenis_kelamin', 'laki-laki')->count(),
        ];
        return [
            'guru' => $guru,
            'count' => $count
        ];
    }

    public function show(Request $request, $nip)
    {
        $guru = Guru::with('user')->where('nip', $nip)->first();
        return response()->json($guru);
    }

    public function store(Request $request,)
    {

        $request->validate([
            'nip' => 'required|numeric|min:6|unique:gurus,nip',
            'nama' => 'required|string|min:4|max:60',
            'alamat' => 'required|min:6|max:50|string',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required|digits:12|numeric',
            'foto_profile' => 'required|image|mimes:png,jpeg,jpg',
            'password' => 'required|alpha_dash|min:6',
        ]);
        $foto_profile = $request->file('foto_profile')->store('FotoProfile/Guru');

        $user = User::create([
            'nip' => $request->nip,
            'name'  => $request->nama,
            'password' => bcrypt($request->password),
        ]);
        $guru = Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'foto_profile' => $foto_profile,
        ]);
        return response()->json(['nip' => $guru->nip, 'nama' => $guru->nama]);
    }

    public function update(Request $request, $nip)
    {
        $guru = Guru::where('nip', $nip)->with('user')->first();

        $request->validate([
            'nip' => 'required|numeric|min:6|unique:gurus,nip',
            'nama' => 'required|string|min:4|max:60',
            'alamat' => 'required|min:6|max:50|string',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required|digits:12|numeric',
            'foto_profile' => 'nullable|image|mimes:png,jpeg,jpg',
            'password' => 'required|alpha_dash|min:6',
        ]);
        if ($request->file('foto_profile')) {
            $foto_profile = $request->file('foto_profile')->store('FotoProfile/Guru') ?? $guru->foto_profile;
        }
        if ($request->password) {
            $guru->user()->update(['password' => bcrypt($request->password)]);
        }
        $guru = $guru->update([
            'nip' => $request->nip,
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
            'no_hp' => $request->no_hp,
            'foto_profile' => $foto_profile,
        ]);

        return response()->json($guru);
    }

    public function delete(Request $request, $nip)
    {
        $guru = Guru::where('nip', '=', $nip)->get()->first();

        $nip = $guru->nip;
        $guru->delete();
        return response()->json("Data guru dengan NIP $nip telah berhasil dihapus, data terkait dengan guru ini juga telah dihapus");
    }
}
