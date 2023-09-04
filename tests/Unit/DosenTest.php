<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase; // the default in laravel 8 not this one

use App\Models\berkas_kenaikan_pangkat_reguler;
use App\Models\my_storage;
use App\Models\pak_kegiatan_penelitian;
use App\Models\status_kenaikan_pangkat;
use App\Models\User;
use Tests\TestCase; // we need to change like this 



class DosenTest extends TestCase
{

     // Environment every test will run this at first
     protected function setUp(): void
     {
         parent::setUp();
         $user = User::where('level','dosen')->first();
 
         $this->actingAs($user);
     }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_dosen()
    {
         // Create a user with the "dosen" level
         $dosen_user = User::create(['pangkat_id' => 2,'name' => 'dosen', 'email' => 'dosen@example.com','level' => 'dosen']);

         // Acting as the "dosen" user
         $this->actingAs($dosen_user);
 
         // Make a GET request to the "dosen.index" route
         $response = $this->get(route('dosen.index'));
 
         // Assert that the response status is 200
         $response->assertStatus(200);

        // Delete the created user to clean up the database
        $dosen_user->delete();
    }

    public function test_user_non_dosen()
    {
         // Create a user with a different level (not "dosen")
         $user = User::create(['name' => 'bubble','email' => 'dosen@example.com' ,'level' => 'pegawai']); 

         // Acting as the non-"dosen" user
         $this->actingAs($user);
 
         // Make a GET request to the "dosen.index" route
         $response = $this->get(route('dosen.index'));
 
         // Assert that the response status is 403 (Forbidden) or any other appropriate status code
         $response->assertStatus(403); 

        // Delete the created user to clean up the database
        $user->delete();
    }

    public function test_page_index()
    {
        $response = $this->get(route('dosen.index'));
        $response->assertStatus(200);
    }

    public function test_function_request_permintaan_kenaikan()
    {
         // Create a user with appropriate permissions or authentication as needed for this test
        $user = User::create([
            'name' => 'kokurate',
            'email' => 'kokurate@example.com',
            'level' => 'dosen',
            'status' => null, // Assuming the status is initially null
        ]);

        $user_status = status_kenaikan_pangkat::create([
            'user_id' => $user->id,
            'status' => 'Permintaan Kenaikan Pangkat Reguler']);

        // Simulate a request to the sudah_bisa_naik_pangkat_reguler method
        $response = $this->actingAs($user)->post(route('dosen.sudah_bisa_naik_pangkat_reguler'), [
            "status" => "Permintaan Kenaikan Pangkat Reguler"
        ]);

        // Assuming a redirect is expected after successful store
        $response->assertStatus(302);

        // Assert that the user's status was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'Permintaan Kenaikan Pangkat Reguler',
        ]);

        // Assert that status_kenaikan_pangkat entry has status set to 'Permintaan Kenaikan Pangkat Reguler'
        $this->assertDatabaseHas('status_kenaikan_pangkats', [
            'user_id' => $user->id,
            'status' => 'Permintaan Kenaikan Pangkat Reguler',
        ]);

        // Delete the test user
        $user->delete();
        $user_status->delete();
    }


    public function test_page_status_kenaikan_pangkat()
    {
        $response = $this->get(route('dosen.status_kenaikan_pangkat'));
        $response->assertStatus(200);
    }

    public function test_page_tambah_pangkat_reguler()
    {
        $dosen = User::create([
            'name' => 'kokurate',
            'email' => 'kokurate@example.com',
            'level' => 'dosen',
            'status' => null, // Assuming the status is initially null
        ]);
        $response = $this->get(route('dosen.tambah_pangkat_reguler', $dosen->email));
        $response->assertStatus(200);


        $dosen->delete();
    }

    public function test_function_tambah_pangkat_reguler()
    {
        $dosen = User::create([
            'name' => 'kokurate',
            'email' => 'kokurate@example.com',
            'level' => 'dosen',
            'status' => 'Sedang Diperiksa',
        ]);


        $response = $this->actingAs($dosen)->post(route('dosen.tambah_pangkat_reguler_store', $dosen->email), [
            "kartu_pegawai_nip_baru_bkn" => "path_to_kartu_pegawai_nip_baru_bkn.pdf",
            "sk_cpns" => "path_to_sk_cpns.pdf",
            "sk_pangkat_terakhir" => "path_to_sk_pangkat_terakhir.pdf",
            "sk_jabfung_terakhir_dan_pak" => "path_to_sk_jabfung_terakhir_dan_pak.pdf",
            "ppk_dan_skp" => "path_to_ppk_dan_skp.pdf",
            "ijazah_terakhir" => "path_to_ijazah_terakhir.pdf", // Optional field
            "sk_tugas_belajar_atau_surat_izin_studi" => "path_to_sk_tugas_belajar_atau_surat_izin_studi.pdf",
            "keterangan_membina_mata_kuliah_dari_jurusan" => "path_to_keterangan_membina_mata_kuliah_dari_jurusan.pdf",
            "surat_pernyataan_setiap_bidang_tridharma" => "path_to_surat_pernyataan_setiap_bidang_tridharma.pdf",
            "user_id" => $dosen->id,
        ]);

        // Assuming a redirect is expected after successful store
        $response->assertStatus(302);


         // Assert that the user's status was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $dosen->id,
            'status' => 'Sedang Diperiksa', // This might change depending on your implementation
        ]);

        // Assert that berkas_kenaikan_pangkat_reguler has the expected data
        // $this->assertDatabaseHas('berkas_kenaikan_pangkat_regulers', [
        //     'user_id' => $dosen->id,
        //     'kartu_pegawai_nip_baru_bkn' =>  $dosen->berkas_kenaikan_pangkat_reguler->kartu_pegawai_nip_baru_bkn,
        //     'sk_cpns' => $dosen->berkas_kenaikan_pangkat_reguler->sk_cpns,
        //     'sk_pangkat_terakhir' => $dosen->berkas_kenaikan_pangkat_reguler->sk_pangkat_terakhir,
        //     'sk_jabfung_terakhir_dan_pak' => $dosen->berkas_kenaikan_pangkat_reguler->sk_jabfung_terakhir_dan_pak,
        //     'ppk_dan_skp' => $dosen->berkas_kenaikan_pangkat_reguler->ppk_dan_skp,
        //     'ijazah_terakhir' => $dosen->berkas_kenaikan_pangkat_reguler->ijazah_terakhir,
        //     'sk_tugas_belajar_atau_surat_izin_studi' => $dosen->berkas_kenaikan_pangkat_reguler->sk_tugas_belajar_atau_surat_izin_studi,
        //     'keterangan_membina_mata_kuliah_dari_jurusan' => $dosen->berkas_kenaikan_pangkat_reguler->keterangan_membina_mata_kuliah_dari_jurusan,
        //     'surat_pernyataan_setiap_bidang_tridharma' => $dosen->berkas_kenaikan_pangkat_reguler->surat_pernyataan_setiap_bidang_tridharma,
        // ]);



        $dosen->delete();
        berkas_kenaikan_pangkat_reguler::where('user_id', $dosen->id)->delete();
    }

    public function test_page_sanggah()
    {
        $response = $this->get(route('dosen.sanggah'));
        $response->assertStatus(200);
        
    }

    public function test_functin_sanggah()
    {
        $dosen = User::create([
            'name' => 'kokurate',
            'email' => 'kokurate@example.com',
            'level' => 'dosen',
            'status' => 'Sedang Diperiksa',
        ]);

  

        $response = $this->actingAs($dosen)->post(route('dosen.sanggah_store', $dosen->email), [
            "kartu_pegawai_nip_baru_bkn" => "path_to_kartu_pegawai_nip_baru_bkn.pdf",
            "sk_cpns" => "path_to_sk_cpns.pdf",
            "sk_pangkat_terakhir" => "path_to_sk_pangkat_terakhir.pdf",
            "sk_jabfung_terakhir_dan_pak" => "path_to_sk_jabfung_terakhir_dan_pak.pdf",
            "ppk_dan_skp" => "path_to_ppk_dan_skp.pdf",
            "ijazah_terakhir" => "path_to_ijazah_terakhir.pdf", // Optional field
            "sk_tugas_belajar_atau_surat_izin_studi" => "path_to_sk_tugas_belajar_atau_surat_izin_studi.pdf",
            "keterangan_membina_mata_kuliah_dari_jurusan" => "path_to_keterangan_membina_mata_kuliah_dari_jurusan.pdf",
            "surat_pernyataan_setiap_bidang_tridharma" => "path_to_surat_pernyataan_setiap_bidang_tridharma.pdf",
        ]);

        // Assuming a redirect is expected after successful store
        $response->assertStatus(302);

        // Reload the $dosen instance from the database after creating it
        $dosen = $dosen->fresh();

        // $dosen = berkas_kenaikan_pangkat_reguler::where('user_id', $dosen->id)->update([
        //     'check_kartu_pegawai_nip_baru_bkn' => 0,
        //     'check_sk_cpns' => 0,
        //     'check_sk_pangkat_terakhir' => 0,
        //     'check_sk_jabfung_terakhir_dan_pak' => 0,
        //     'check_ppk_dan_skp' => 0,
        //     'check_ijazah_terakhir' => 0,
        //     'check_sk_tugas_belajar_atau_surat_izin_studi' => 0,
        //     'check_keterangan_membina_mata_kuliah_dari_jurusan' => 0,
        //     'check_surat_pernyataan_setiap_bidang_tridharma' => 0,
        // ]);

         // Assert that the user's status was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $dosen->id,
            'status' => 'Sedang Diperiksa', // This might change depending on your implementation
        ]);

        // Assert that berkas_kenaikan_pangkat_reguler has the expected data
        // $this->assertDatabaseHas('berkas_kenaikan_pangkat_regulers', [
        //     'user_id' => $dosen->id,
        //     'kartu_pegawai_nip_baru_bkn' =>  $dosen->berkas_kenaikan_pangkat_reguler->kartu_pegawai_nip_baru_bkn,
        //     'sk_cpns' => $dosen->berkas_kenaikan_pangkat_reguler->sk_cpns,
        //     'sk_pangkat_terakhir' => $dosen->berkas_kenaikan_pangkat_reguler->sk_pangkat_terakhir,
        //     'sk_jabfung_terakhir_dan_pak' => $dosen->berkas_kenaikan_pangkat_reguler->sk_jabfung_terakhir_dan_pak,
        //     'ppk_dan_skp' => $dosen->berkas_kenaikan_pangkat_reguler->ppk_dan_skp,
        //     'ijazah_terakhir' => $dosen->berkas_kenaikan_pangkat_reguler->ijazah_terakhir,
        //     'sk_tugas_belajar_atau_surat_izin_studi' => $dosen->berkas_kenaikan_pangkat_reguler->sk_tugas_belajar_atau_surat_izin_studi,
        //     'keterangan_membina_mata_kuliah_dari_jurusan' => $dosen->berkas_kenaikan_pangkat_reguler->keterangan_membina_mata_kuliah_dari_jurusan,
        //     'surat_pernyataan_setiap_bidang_tridharma' => $dosen->berkas_kenaikan_pangkat_reguler->surat_pernyataan_setiap_bidang_tridharma,
        // ]);



        $dosen->delete();
        berkas_kenaikan_pangkat_reguler::where('user_id', $dosen->id)->delete();
    }

    public function test_page_storage(){
        $response = $this->get(route('dosen.storage'));
        $response->assertStatus(200);
    }

    public function test_function_storage(){

        $dosen = User::create([
            'name' => 'storage testing',
            'email' => 'storage@example.com',
            'level' => 'dosen',
        ]);

        $response = $this->actingAs($dosen)->post(route('dosen.storage_store'),[
            'path' => 'dosen/storage/example.pdf',
            'nama' => 'storage testing'
        ]);

        // Assuming a redirect is expected after successful store
        $response->assertStatus(302);

        // $storage = my_storage::where('nama','storage testing')->update(['user_id' => $dosen->id]);


        $dosen->delete();
        my_storage::where('user_id', $dosen->id)->delete();

    }

    public function test_page_simulasi()
    {
        $response = $this->get(route('dosen.simulasi')) AND 
                    $this->get(route('pendidikan-dan-pengajaran')) AND
                    $this->get(route('penelitian')) AND
                    $this->get(route('pengabdian-pada-masyarakat')) AND
                    $this->get(route('penunjang-tri-dharma-pt')) ;
        $response->assertStatus(200);
    }

    public function test_function_simulasi()
    {
        // only take penelitian as example

        $dosen = User::create([
            'name' => 'simulasi testing',
            'email' => 'simulasi@example.com',
            'level' => 'dosen',
        ]);

        $response = $this->actingAs($dosen)->post(route('penelitian_tambah_store'),[
            "kegiatan" => "testing simulasi",
            "tipe_kegiatan" => "Hasil Penelitian Atau Hasil Pemikiran Yang Didesiminasikan",
            "tahun_ajaran_id" => "1",
            "angka_kredit" => "20",
            "bukti" => "dosen/dosen-fakultas-ilmu-pendidikan/1693812738-testing-simulasi.pdf",
            "kode" => "II.A.1.c.1.a",
            "nilai_kegiatan" => "Internasional",
            "komponen_kegiatan" => "Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan",
            "slug" => "testing-simulasi-EfuFj",
            "user_id" => $dosen->id,
            "kategori_pak_id" => 2,
        ]);

        // Assuming a redirect is expected after successful store
        $response->assertStatus(302);

        // Assert  the expected data
        // $this->assertDatabaseHas('pak_kegiatan_penelitians', [
        //     "kegiatan" => "testing simulasi",
        //     "tipe_kegiatan" => "Hasil Penelitian Atau Hasil Pemikiran Yang Didesiminasikan",
        //     "tahun_ajaran_id" => "1",
        //     "angka_kredit" => "20",
        //     "bukti" => "dosen/dosen-fakultas-ilmu-pendidikan/1693812738-testing-simulasi.pdf",
        //     "kode" => "II.A.1.c.1.a",
        //     "nilai_kegiatan" => "Internasional",
        //     "komponen_kegiatan" => "Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan",
        //     "slug" => "testing-simulasi-EfuFj",
        //     "user_id" => $dosen->id,
        //     "kategori_pak_id" => 2,
        // ]);

        $dosen->delete();
        pak_kegiatan_penelitian::where('user_id', $dosen->id)->delete();

    }

    public function test_function_edit_simulasi()
    {
        $dosen = User::create([
            'name' => 'simulasi edit testing',
            'email' => 'edit@example.com',
            'level' => 'dosen',
        ]);

        $berkas_simulasi = pak_kegiatan_penelitian::create([
            "kegiatan" => "testing simulasi",
            "tipe_kegiatan" => "Hasil Penelitian Atau Hasil Pemikiran Yang Didesiminasikan",
            "tahun_ajaran_id" => "1",
            "angka_kredit" => "20",
            "bukti" => "dosen/dosen-fakultas-ilmu-pendidikan/1693812738-testing-simulasi.pdf",
            "kode" => "II.A.1.c.1.a",
            "nilai_kegiatan" => "Internasional",
            "komponen_kegiatan" => "Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan",
            "slug" => "testing-simulasi-EfuFj",
            "user_id" => $dosen->id,
            "kategori_pak_id" => 2,
        ]);

        $response = $this->actingAs($dosen)->post(route('penelitian_edit_store', $berkas_simulasi->slug),[
            "kegiatan" => "testing simulasi edited",
            "angka_kredit" => 15,
        ]);

        // Assuming a redirect is expected after successful store
        $response->assertStatus(302);


        $dosen->delete();
        pak_kegiatan_penelitian::where('user_id', $dosen->id)->delete();
    }



}
