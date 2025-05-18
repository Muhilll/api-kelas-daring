<?php

use App\Http\Controllers\AnggotaAbsensi;
use App\Http\Controllers\AnggotaKelasController;
use App\Http\Controllers\AnggotaTugasController;
use App\Http\Controllers\PemilikKelasController;
use App\Http\Controllers\MhsController;
use App\Http\Controllers\PemilikAbsensi;
use App\Http\Controllers\PemilikAgtController;
use App\Http\Controllers\PemilikPengumumanController;
use App\Http\Controllers\PemilikTugasController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::get('user/get-kelas', [UserController::class, 'getDataKelas']);
Route::get('user/get-profil', [UserController::class, 'getDataUser']);
Route::post('user/edit-profil', [UserController::class, 'editUser']);
Route::post('/gabung-kelas',[UserController::class, 'gabungKelas']);

Route::post('/buat-kelas', [PemilikKelasController::class, 'buatKelas']);
Route::get('pemilik/get-kelas', [PemilikKelasController::class, 'getDataKelas']);
Route::get('/get-kelas-detail', [PemilikKelasController::class, 'getDetailKelas']);
Route::post('/edit-kelas', [PemilikKelasController::class, 'editKelas']);
Route::delete('/hapus-kelas', [PemilikKelasController::class, 'hapusKelas']);
Route::get('pemilik/get-pengumuman', [PemilikPengumumanController::class, 'getDataPengumuman']);
Route::post('/buat-pengumuman', [PemilikPengumumanController::class, 'buatPengumuman']);
Route::get('/get-pengumuman-detail', [PemilikPengumumanController::class, 'getDetailPengumuman']);
Route::post('/edit-pengumuman', [PemilikPengumumanController::class, 'editPengumuman']);
Route::delete('/hapus-pengumuman', [PemilikPengumumanController::class, 'hapusPengumuman']);
Route::get('/pemilik/get-agt', [PemilikAgtController::class, 'getDataAgt']);
Route::delete('/pemilik/hapus-agt', [PemilikAgtController::class, 'hapusAgt']);
Route::get('pemilik/get-tugas', [PemilikTugasController::class, 'getDataTugas']);
Route::post('/buat-tugas', [PemilikTugasController::class, 'buatTugas']);
Route::get('/data-pengumpulan-tugas', [PemilikTugasController::class, 'getDataPengumpulanTugas']);
Route::get('pemilik/download-tugas', [PemilikTugasController::class, 'downloadTugas']);
Route::post('pemilik/buat-absensi', [PemilikAbsensi::class, 'buatAbsen']);
Route::get('pemilik/get-absensi', [PemilikAbsensi::class, 'getDataAbsensi']);
Route::get('pemilik/get-kehadiran', [PemilikAbsensi::class, 'getDataKehadiran']);


Route::get('/anggota/get-kelas',[AnggotaKelasController::class, 'getDataKelas']);
Route::get('/get-pemilik-kelas', [AnggotaKelasController::class, 'getPemilikKelas']);
Route::post('/get-data-agtkelas', [AnggotaKelasController::class, 'getDataAgtKelas']);
Route::get('anggota/get-absensi', [AnggotaAbsensi::class, 'getAbsensi']);
Route::post('anggota/cek-kehadiran', [AnggotaAbsensi::class, 'cekKehadiran']);
Route::post('/submit-kehadiran', [AnggotaAbsensi::class, 'submitKehadiran']);
Route::get('/cek-tugas', [AnggotaTugasController::class, 'cekTugas']);
Route::post('/proses-upload-tugas', [AnggotaTugasController::class, 'prosesUploadTugas']);
Route::delete('/hapus-pengumpulan-tugas', [AnggotaTugasController::class, 'hapusTugas']);