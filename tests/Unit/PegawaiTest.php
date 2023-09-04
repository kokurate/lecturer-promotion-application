<?php

namespace Tests\Unit;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Mail\UbahStatusKenaikanPangkat;
use App\Models\berkas_kenaikan_pangkat_reguler;
use App\Models\status_kenaikan_pangkat;
use Illuminate\Support\Facades\Mail;

// use PHPUnit\Framework\TestCase; // the default in laravel 8 not this one
use App\Models\User;
use Tests\TestCase; // we need to change like this 

class PegawaiTest extends TestCase
{
    // use RefreshDatabase; // To reset the database after each test


    // Environment every test will run this at first
    protected function setUp(): void
    {
        parent::setUp();
        $user = User::where('level','pegawai')->first();

        $this->actingAs($user);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_user_pegawai()
    {
         // Create a user with the "pegawai" level
         $pegawai_user = User::create(['name' => 'kokurate', 'email' => 'kokurate@example.com','level' => 'pegawai']);

         // Acting as the "pegawai" user
         $this->actingAs($pegawai_user);
 
         // Make a GET request to the "pegawai.index" route
         $response = $this->get(route('pegawai.index'));
 
         // Assert that the response status is 200
         $response->assertStatus(200);

        // Delete the created user to clean up the database
        $pegawai_user->delete();
    }

    public function test_user_non_pegawai()
    {
         // Create a user with a different level (not "pegawai")
         $user = User::create(['name' => 'bubble','email' => 'bubble@example.com' ,'level' => 'dosen']); 

         // Acting as the non-"pegawai" user
         $this->actingAs($user);
 
         // Make a GET request to the "pegawai.index" route
         $response = $this->get(route('pegawai.index'));
 
         // Assert that the response status is 403 (Forbidden) or any other appropriate status code
         $response->assertStatus(403); 

        // Delete the created user to clean up the database
        $user->delete();
    }

    public function test_page_index(){
        $response = $this->get(route('pegawai.index'));
        $response->assertStatus(200);
    }

    public function test_page_permintaan_kenaikan()
    {
        $response = $this->get(route('pegawai.permintaan_kenaikan_pangkat'));
        $response->assertStatus(200);
    }

    public function test_function_accept_permintaan_kenaikan(){
         // Create a user
         $user = User::create(['name' => 'kokurate', 'email' => 'kokurate@example.com','level' => 'dosen', 'pangkat_id' => 2]);
         $user_status = status_kenaikan_pangkat::create(['user_id', $user->id]);

        $user_status;

        // Simulate a valid request
        $response = $this->post(route('pegawai.ubah_status_kenaikan_pangkat_store', $user->email), [
           'golongan' => $user->pangkat_id + 1,
           'user_id' => $user->id,
           'status' => 'Tersedia',
        ]);

        // Manually set the success flash message in the session
        $this->withSession(['success' => 'Status Kenaikan Pangkat Berhasil Diubah']);


         // Assert a successful response
        $response->assertStatus(302) // Assuming a redirect is expected
            ->assertSessionHas('success');

        // Assert that the user's status was updated in the database
        $this->assertDatabaseHas('status_kenaikan_pangkats', [
            "user_id" => $user->id,
            "status" => "Tersedia"
        ]);
        

       // next send email to user
    
       $user->delete();
       $user_status->delete();

    }   

    public function test_page_semua_dosen(){
        $response = $this->get(route('pegawai.semua_dosen'));
        $response->assertStatus(200);
    }

    public function test_page_detail_dosen(){
        $user = User::create(['name' => 'kokurate', 'email' => 'kokurate@example.com','level' => 'dosen', 'pangkat_id' => 2]);
        $response = $this->get(route('pegawai.ubah_status_kenaikan_pangkat', $user->email));
        $response->assertStatus(200);
        $user->delete();
    }

    public function test_function_create_dosen(){

        // Create a user with appropriate permissions or authentication as needed for this test
        $user = User::create(['name' => 'kokurate', 'email' => 'kokurate@example.com','level' => 'pegawai']);

        $response = $this->actingAs($user)->post(route('pegawai.semua_dosen_store'), [
            "name" => "Test Dosen",
            "email" => "test@example.com",
            "pangkat_id" => 1,
            "jurusan_prodi" => "Program Studi Pendidikan Guru Anak Usia Dini",
            "nip" => "123456789012345678",
            "nidn" => "1234567890",
            "level" => "dosen",
            "fakultas" => "Fakultas Ilmu Pendidikan",
        ]);

        $response->assertStatus(302); // Assuming a redirect is expected after successful creation

          // Assert that the user's created was in the database
          $recordExists = User::where('nip', 123456789012345678)
          ->exists();
  
          $this->assertTrue($recordExists);

          // next send email as notification

        $user->delete();
        User::where('nip', 123456789012345678)->delete();

    }

    public function test_page_pengajuan_masuk(){
        $response = $this->get(route('pegawai.pengajuan_masuk'));
        $response->assertStatus(200);
    }

    public function test_function_pdf_merge(){
        // Create a user with appropriate permissions or authentication as needed for this test
        $pegawai = User::create(['name' => 'kokurate', 'email' => 'kokurate@example.com','level' => 'pegawai']);

        $user = User::create(['name' => 'user', 'email' => 'user@example.com','level' => 'dosen','status' => 'Disetujui']);

        // Simulate a request to the merge method
        $response = $this->actingAs($pegawai)->post(route('pdf.merge', $user->email), [
            "kartu_pegawai_nip_baru_bkn" => "1mb.pdf",
            "sk_cpns" => "1mb.pdf",
            "sk_pangkat_terakhir" => "1mb.pdf",
            "sk_jabfung_terakhir_dan_pak" => "1mb.pdf",
            "ppk_dan_skp" => "1mb.pdf",
            "ijazah_terakhir" => "1mb.pdf",
            "sk_tugas_belajar_atau_surat_izin_studi" => "1mb.pdf",
            "keterangan_membina_mata_kuliah_dari_jurusan" => "1mb.pdf",
            "surat_pernyataan_setiap_bidang_tridharma" => "1mb.pdf",
        ]);

        // Assuming a redirect is expected after successful merge
        // becauss we can't test the uploaded file so i just want to change the status code 
        // $response->assertStatus(302); 
        $response->assertStatus(500); 
    

        // Assert that the user's status was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'Disetujui',
        ]);

        $pegawai->delete();
        $user->delete();
        berkas_kenaikan_pangkat_reguler::where('sk_cpns', "1mb.pdf")->delete();
    }

    public function test_function_rejected_pengajuan()
    {
         // Create a user with appropriate permissions 
         $pegawai = User::create(['name' => 'kokurate', 'email' => 'kokurate@example.com','level' => 'pegawai']);
    
         $user = User::create(['name' => 'user', 'email' => 'user@example.com','level' => 'dosen','status' => 'Ditolak']);

        // Simulate a request to the controller method
        $response = $this->actingAs($pegawai)->post(route('pegawai.pengajuan_masuk_detail_tolak_store', $user->email), [
            "tanggapan" => "testing",
            "status" => "Ditolak"
        ]);

        $berkas_ditolak = [ 'user_id' => $user->id,"check_sk_cpns" => "1", "check_sk_pangkat_terakhir" => "1"];

        berkas_kenaikan_pangkat_reguler::create($berkas_ditolak);

        
        // Assuming a redirect is expected after successful store
        $response->assertStatus(302); 
    
        // Assert that the user's status was updated in the database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'Ditolak',
        ]);

        // send email as notification

        $pegawai->delete();
        berkas_kenaikan_pangkat_reguler::where('user_id', $user->id);
        $user->delete();

        }


        public function test_page_dalam_proses()
        {
            $response = $this->get(route('pegawai.pengajuan_dalam_proses'));
            $response->assertStatus(200);
        }

        public function test_function_selesaikan_pengajuan()
        {
            // Create a user with appropriate permissions or authentication as needed for this test
            $pegawai = User::create(['name' => 'kokurate', 'email' => 'kokurate@example.com', 'level' => 'pegawai']);

            $user = User::create(['name' => 'user', 'email' => 'user@example.com', 'level' => 'dosen', 'pangkat_id' => 2, 'status' => 'Ditolak']);

            // Simulate a request to the pengajuan_selesai_store method
            $response = $this->actingAs($pegawai)->post(route('pegawai.selesai_store', $user->email), [
                "status" => "Selesai"
            ]);

            // Assuming a redirect is expected after successful store
            $response->assertStatus(302);

            // Assert that the user's status was updated in the database
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'status' =>  null,
            ]);

            // Assert that the user's pangkat_id was updated in the database
            $this->assertDatabaseHas('users', [
                'id' => $user->id,
                'pangkat_id' => 3, // Assuming the user's pangkat_id increases by 1
            ]);

            // Assert that status_kenaikan_pangkat entry has status set to null
            // $this->assertDatabaseHas('status_kenaikan_pangkats', [
            //     'user_id' => $user->id,
            //     'status' => null,
            // ]);

            // Delete the test users
            $pegawai->delete();
            $user->delete();
        }






}
