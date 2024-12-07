<?php

use App\Http\Controllers\LaporanPenilaianKepsek;
use App\Http\Controllers\LaporanPenilaianSiswa;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('lapora-history-penilaian-kepsek', [LaporanPenilaianKepsek::class, 'index']);

Route::get('lapora-history-penilaian-siswa/{id}', [LaporanPenilaianSiswa::class, 'history_penilaian']);
