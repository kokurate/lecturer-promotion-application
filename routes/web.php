<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PegawaiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[AuthController::class, ('login')])->name('login');

// ============================= Dosen =========================== 
Route::get('/dosen',[DosenController::class,('index')])->name('dosen.index');
Route::get('/dosen/kenaikan-pangkat-reguler/tambah',[DosenController::class,('tambah_pangkat_reguler')])->name('dosen.tambah_pangkat_reguler');


// ============================= Pegawai =========================== 
Route::get('/pegawai',[PegawaiController::class,('index')])->name('pegawai.index');
