<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\PdfController;
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

// PDF
Route::get('/pdf/{path}', [PdfController::class,('show')] )->name('pdf.show');


// Reset Password Alternative
Route::get('/forgot-password',[AuthController::class, ('show_forgot_password')])->name('show_forgot_password');
Route::post('/forgot-password',[AuthController::class, ('store_forgot_password')])->name('store_forgot_password');
Route::get('/reset-password/{user:my_token}',[AuthController::class, ('show_reset_password')])->name('show_reset_password');
Route::post('/reset-password/{user:my_token}',[AuthController::class, ('store_reset_password')])->name('store_reset_password');

// ============================= Dosen =========================== 
Route::middleware('auth', 'level:admin,dosen',)->group(function () {
    Route::get('/dosen',[DosenController::class,('index')])->name('dosen.index');
    
    // Storage
    Route::get('/dosen/storage',[DosenController::class,('storage')])->name('dosen.storage');
    Route::post('/dosen/storage',[DosenController::class,('storage_store')])->name('dosen.storage_store');
    Route::delete('/dosen/storage/{my_storage:filename}',[DosenController::class,('storage_destroy')])->name('dosen.storage_destroy');

    // tambah berkas kenaikan pangkat reguler
    Route::get('/dosen/kenaikan-pangkat-reguler/tambah/{user:email}',[DosenController::class,('tambah_pangkat_reguler')])->name('dosen.tambah_pangkat_reguler');
    Route::post('/dosen/kenaikan-pangkat-reguler/tambah/{user:email}',[DosenController::class,('tambah_pangkat_reguler_store')])->name('dosen.tambah_pangkat_reguler_store');
  
    // sanggah 
    Route::get('/dosen/kenaikan-pangkat-reguler/sanggah',[DosenController::class,('sanggah')])->name('dosen.sanggah');
    Route::post('/dosen/kenaikan-pangkat-reguler/sanggah/{user:email}',[DosenController::class,('sanggah_store')])->name('dosen.sanggah_store');
   
    
    // status kenaikan pangkat
    Route::get('/dosen/status-kenaikan-pangkat', [DosenController::class,('status_kenaikan_pangkat')])->name('dosen.status_kenaikan_pangkat');

    // Verifikasi NIP dan NIDN
    // Route::get('/dosen/verifikasi-nip-dan-nidn', [DosenController::class,('verify_nip_and_nidn')])->name('dosen.verify_nip_and_nidn');
    // Route::post('/dosen/verifikasi-nip-dan-nidn', [DosenController::class,('verify_nip_and_nidn_store')])->name('dosen.verify_nip_and_nidn_store');

    //  ======= ======   Simulasi
    Route::get('dosen/simulasi-pak',[DosenController::class,('simulasi')])->name('dosen.simulasi');
    
    // Pendidikan dan Pengajaran
    Route::get('dosen/simulasi-pak/pendidikan-dan-pengajaran/',[DosenController::class,('pendidikan_dan_pengajaran')])->name('pendidikan-dan-pengajaran');
    Route::get('dosen/simulasi-pangkat/pendidikan-dan-pengajaran/{detail}',[DosenController::class,('pendidikan_dan_pengajaran_detail')])->name('pendidikan_dan_pengajaran_detail');
    Route::get('dosen/simulasi-pak/pendidikan-dan-pengajaran/tambah',[DosenController::class,('pendidikan_dan_pengajaran_tambah')])->name('pendidikan_dan_pengajaran_tambah');
    Route::post('dosen/simulasi-pak/pendidikan-dan-pengajaran/tambah',[DosenController::class,('pendidikan_dan_pengajaran_tambah_store')])->name('pendidikan_dan_pengajaran_tambah_store');
    Route::delete('dosen/simulasi-pak/pendidikan-dan-pengajaran/{slug}',[DosenController::class,('pendidikan_dan_pengajaran_destroy')])->name('pendidikan_dan_pengajaran_destroy');

    
    Route::get('dosen/simulasi-pak/penelitian',[DosenController::class,('penelitian')])->name('penelitian');
    Route::get('dosen/simulasi-pak/pengabdian-pada-masyarakat',[DosenController::class,('pengabdian_pada_masyarakat')])->name('pengabdian-pada-masyarakat');
    Route::get('dosen/simulasi-pak/penunjang-tri-dharma-pt',[DosenController::class,('penunjang_tri_dharma_pt')])->name('penunjang-tri-dharma-pt');

});



// ============================= Pegawai =========================== 
Route::middleware('auth', 'level:admin,pegawai')->group(function () {
    Route::get('/pegawai',[PegawaiController::class,('index')])->name('pegawai.index');
    Route::get('/pegawai/semua-dosen', [PegawaiController::class,('semua_dosen')])->name('pegawai.semua_dosen');
    Route::post('/pegawai/semua-dosen', [PegawaiController::class,('semua_dosen_store')])->name('pegawai.semua_dosen_store');
    

    Route::get('/pegawai/ubah-status-kenaikan-pangkat/{user:email}', [PegawaiController::class,('ubah_status_kenaikan_pangkat')])->name('pegawai.ubah_status_kenaikan_pangkat');
    Route::post('/pegawai/ubah-status-kenaikan-pangkat/{user:email}', [PegawaiController::class,('ubah_status_kenaikan_pangkat_store')])->name('pegawai.ubah_status_kenaikan_pangkat_store');

    // Pengajuan Masuk / Sanggah
    Route::get('/pegawai/semua-pengajuan-masuk', [PegawaiController::class, ('pengajuan_masuk')])->name('pegawai.pengajuan_masuk');
    Route::get('/pegawai/pengajuan-masuk/{user:email}', [PegawaiController::class, ('pengajuan_masuk_user')])->name('pegawai.pengajuan_masuk_user');
    Route::get('/pegawai/pengajuan-masuk/detail/{user:email}', [PegawaiController::class, ('pengajuan_masuk_detail')])->name('pegawai.pengajuan_masuk_detail');
    
    // Dalam Proses
    Route::get('/pegawai/pengajuan-dalam-proses', [PegawaiController::class, ('pengajuan_dalam_proses')])->name('pegawai.pengajuan_dalam_proses');
    
    // selesai
    Route::post('/pegawai/pengajuan-selesai/{user:email}', [PegawaiController::class, ('pengajuan_selesai_store')])->name('pegawai.selesai_store');


    // tolak
    Route::post('/pegawai/pengajuan-masuk/detail/{user:email}/tolak', [PegawaiController::class, ('pengajuan_masuk_detail_tolak_store')])->name('pegawai.pengajuan_masuk_detail_tolak_store');
    // terima
    Route::post('/pegawai/pengajuan-masuk/detail/{user:email}/setuju', [PdfController::class, ('merge')])->name('pdf.merge');


});

Route::middleware('auth', 'level:admin')->group(function () {
    Route::get('/admin',[AdminController::class,('index')])->name('admin.index');
    
    // register
    Route::get('/admin/register-pegawai',[AdminController::class,('register_pegawai')])->name('admin.register_pegawai');
    Route::post('/admin/register-pegawai',[AdminController::class,('register_pegawai_store')])->name('admin.register_pegawai_store');
    
    Route::get('/admin/simulasi',[AdminController::class,('simulasi')])->name('admin.simulasi');
    Route::post('/admin/simulasi/tambah-tahun-ajaran-store',[AdminController::class,('tambah_tahun_ajaran_store')])->name('admin.simulasi_tambah.tahun_ajaran_store');
    Route::delete('/admin/simulasi/tambah-tahun-ajaran_destroy/{id}',[AdminController::class,('tambah_tahun_ajaran_destroy')])->name('admin.simulasi.tambah_tahun_ajaran_destroy');
    Route::post('/admin/simulasi/tambah-tahun-ajaran_update/{id}',[AdminController::class,('tambah_tahun_ajaran_update')])->name('admin.simulasi_tambah.tahun_ajaran_update');
    Route::delete('/admin/simulasi/truncate-tahun-ajaran',[AdminController::class,('truncate_tahun_ajaran')])->name('truncate_tahun_ajaran');
    
    Route::delete('/admin/simulasi/kategori-simulasi/{id}/destroy',[AdminController::class,('kategori_simulasi_destroy')])->name('admin.simulasi.kategori_simulasi_destroy');
    Route::post('/admin/simulasi/kategori-simulasi/tambah',[AdminController::class,('kategori_simulasi_store')])->name('admin.simulasi.kategori_simulasi_store');
    Route::delete('/admin/simulasi/kategori-simulasi/pendidikan-dan-pengajaran/tipe-kegiatan/{id}/destroy',[AdminController::class,('tipe_kegiatan_pendidikan_dan_pengajaran_destroy')])->name('admin.simulasi.tipe_kegiatan_pendidikan_dan_pengajaran_destroy');
    Route::post('/admin/simulasi/kategori-simulasi/pendidikan-dan-pengajaran/tipe-kegiatan/store',[AdminController::class,('tipe_kegiatan_pendidikan_dan_pengajaran_store')])->name('admin.simulasi.tipe_kegiatan_pendidikan_dan_pengajaran_store');




});
