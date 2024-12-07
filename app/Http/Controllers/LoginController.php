<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function store(Request $request)
    {

        $request->validate([
            'nip' => 'required|numeric|digits:6',
            'password' => 'required|alpha_dash|min:4'
        ]);
        $user = User::where('nip', $request->nip)->first();

        if (! $user or ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(
                ['nip' => 'NIP/NIS atau password salah, silahkan masukkan data user yang benar'],
            );
        }
        $user->tokens()->delete();
        $token =  $user->createToken('web-token')->plainTextToken;
        return (new UserResource($user))->additional(compact('token'));
    }

    public function destroy(Request $request)
    {
        $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();
        return response()->json(['message' => 'Logout Berhasil']);
    }
}
