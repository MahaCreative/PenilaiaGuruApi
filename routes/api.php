<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataKelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\GuruHistory;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LaporanPenilaianGuru;
use App\Http\Controllers\LaporanPenilaianKepsek;
use App\Http\Controllers\LaporanPenilaianSiswa;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PenilaianKepsekController;
use App\Http\Controllers\PenilaianSiswaController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\SiswaController;
use App\Models\PenilaianKepsek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [LoginController::class, 'store']);

Route::post('delete-guru/{nip}', [GuruController::class, 'delete']);



Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [LoginController::class, 'me']);
    Route::delete('logout', [LoginController::class, 'destroy']);
    Route::post('create-guru', [GuruController::class, 'store']);
    Route::get('get-guru', [GuruController::class, 'index']);
    Route::get('detail-guru/{nip}', [GuruController::class, 'show']);
    Route::put('update-guru/{nip}', [GuruController::class, 'update']);



    Route::get('get-data-kelas', [DataKelasController::class, 'index']);
    Route::get('show-daftar-siswa/{kelas}', [DataKelasController::class, 'show_siswa']);
    Route::post('create-data-kelas', [DataKelasController::class, 'create']);
    Route::post('delete-data-kelas/{kode_kelas}', [DataKelasController::class, 'delete']);

    Route::get('get-data-siswa', [SiswaController::class, 'index']);
    Route::get('show-data-siswa/{nis}', [SiswaController::class, 'show']);
    Route::post('create-data-siswa', [SiswaController::class, 'create']);
    Route::delete('delete-data-siswa/{nis}', [SiswaController::class, 'delete']);

    Route::get('get-data-kriteria', [KriteriaController::class, 'index']);
    Route::post('create-data-kriteria', [KriteriaController::class, 'create']);
    Route::delete('delete-data-kriteria/{id}', [KriteriaController::class, 'delete']);

    Route::get('get-data-periode', [PeriodeController::class, 'index']);
    Route::get('show-data-periode/{id}', [PeriodeController::class, 'show']);
    Route::post('create-data-periode', [PeriodeController::class, 'create']);
    Route::delete('delete-data-periode/{id}', [PeriodeController::class, 'delete']);

    Route::get('get-guru-belum-dinilai/{id}', [PenilaianKepsekController::class, 'index']);
    Route::post('create-data-nilai-kepsek/{id}', [PenilaianKepsekController::class, 'create']);
    Route::post('proses-penilaian-kepsek/{id}', [PenilaianKepsekController::class, 'proses_penilaian']);
    Route::get('history-penilaian-kepsek/{id}', [PenilaianKepsekController::class, 'history_penilaian']);
    Route::get('kepsek/history-penilaian-siswa/{id}', [PenilaianKepsekController::class, 'history_penilaian_siswa']);
    Route::get('get-data-nilai-kepsek/{id}', [PenilaianKepsekController::class, 'show']);
    Route::get('rangking-sementara-penilaian-kepsek', [PenilaianKepsekController::class, 'rangking_sementara']);
    Route::get('selesaikan_penilaian/{id}', [PenilaianKepsekController::class, 'selesaikan_penilaian']);

    // Penilaian Siswa
    Route::get('create-penilaian-siswa/{id}', [PenilaianSiswaController::class, 'index']);
    Route::post('store-data-nilai-siswa/{id}', [PenilaianSiswaController::class, 'store_nilai']);
    Route::get('history-penilaian-siswa/{id}', [PenilaianSiswaController::class, 'history_penilaian']);
    Route::post('proses-penilaian-siswa/{id}', [PenilaianSiswaController::class, 'proses_penilaian']);
    Route::get('rangking-sementara-penilaian-siswa', [PenilaianSiswaController::class, 'rangking_sementara']);

    Route::get('guru/history-penilaian-siswa/{id}', [GuruHistory::class, 'history_penilaian_siswa']);
    Route::get('guru/history-penilaian-kepsek/{id}', [GuruHistory::class, 'history_penilaian_kepsek']);
    Route::get('guru/ranking-kepsek/', [GuruHistory::class, 'ranking_kepsek']);

    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::get('lapora-history-penilaian-kepsek/{id}', [LaporanPenilaianKepsek::class, 'history_penilaian']);

    Route::get('lapora-history-penilaian-siswa/{id}', [LaporanPenilaianSiswa::class, 'history_penilaian']);

    Route::get('laporan-penilaian-guru/{id}', [LaporanPenilaianGuru::class, 'index']);
});
