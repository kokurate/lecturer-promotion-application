<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PdfFileController;
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
// bubble group
// Route::group( ['middleware' => ['auth', ]],(function(){
// }));


Route::get('/',[AuthController::class, ('login')])->name('login');
Route::post('authenticate',[AuthController::class,'authenticate'])->name('authenticate');
Route::post('logout',[AuthController::class,'logout'])->name('logout');

// ============================= Dosen =========================== 
Route::middleware('auth', 'level:admin,dosen')->group(function () {
    Route::get('/dosen',[DosenController::class,('index')])->name('dosen.index');
    
    // Storage
    Route::get('/dosen/storage',[DosenController::class,('storage')])->name('dosen.storage');
    Route::post('/dosen/storage',[DosenController::class,('storage_store')])->name('dosen.storage_store');
    Route::delete('/dosen/storage/{my_storage:filename}',[DosenController::class,('storage_destroy')])->name('dosen.storage_destroy');

    // tambah berkas kenaikan pangkat reguler
    Route::get('/dosen/kenaikan-pangkat-reguler/tambah/{user:email}',[DosenController::class,('tambah_pangkat_reguler')])->name('dosen.tambah_pangkat_reguler');
    Route::post('/dosen/kenaikan-pangkat-reguler/tambah/{user:email}',[DosenController::class,('tambah_pangkat_reguler_store')])->name('dosen.tambah_pangkat_reguler_store');
    
});



// ============================= Pegawai =========================== 
Route::middleware('auth', 'level:admin,pegawai')->group(function () {
    Route::get('/pegawai',[PegawaiController::class,('index')])->name('pegawai.index');
    Route::get('/pegawai/semua-dosen', [PegawaiController::class,('semua_dosen')])->name('pegawai.semua_dosen');
    Route::post('/pegawai/semua-dosen', [PegawaiController::class,('semua_dosen_store')])->name('pegawai.semua_dosen_store');
    

    Route::get('/pegawai/ubah-status-kenaikan-pangkat/{user:email}', [PegawaiController::class,('ubah_status_kenaikan_pangkat')])->name('pegawai.ubah_status_kenaikan_pangkat');
    Route::post('/pegawai/ubah-status-kenaikan-pangkat/{user:email}', [PegawaiController::class,('ubah_status_kenaikan_pangkat_store')])->name('pegawai.ubah_status_kenaikan_pangkat_store');


});

Route::middleware('auth', 'level:admin')->group(function () {
    Route::get('/admin',[AdminController::class,('index')])->name('admin.index');
});
