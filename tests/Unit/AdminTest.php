<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase; // the default in laravel 8 not this one

use App\Models\tahun_ajaran;
use Tests\TestCase; // we need to change like this 


use App\Models\User;



class AdminTest extends TestCase
{

     // Environment every test will run this at first
     protected function setUp(): void
     {
         parent::setUp();
         $user = User::where('level','admin')->first();
 
         $this->actingAs($user);
     }



    /**
     * A basic unit test example.
     *
     * @return void
     */
    

     public function test_user_admin()
     {
          // Create a user with the "dosen" level
          $admin_user = User::create(['name' => 'admin', 'email' => 'admin@example.com','level' => 'admin']);
 
          // Acting as the "dosen" user
          $this->actingAs($admin_user);
  
          // Make a GET request to the "dosen.index" route
          $response = $this->get(route('admin.index'));
  
          // Assert that the response status is 200
          $response->assertStatus(200);
 
         // Delete the created user to clean up the database
         $admin_user->delete();
     }

     public function test_user_non_admin()
     {
          // Create a user with a different level (not "dosen")
          $user = User::create(['name' => 'bubble','email' => 'nonadmin@example.com' ,'level' => 'pegawai']); 
 
          // Acting as the non-"dosen" user
          $this->actingAs($user);
  
          // Make a GET request to the "dosen.index" route
          $response = $this->get(route('admin.index'));
  
          // Assert that the response status is 403 (Forbidden) or any other appropriate status code
          $response->assertStatus(403); 
 
         // Delete the created user to clean up the database
         $user->delete();
     }

     public function test_page_index()
     {
        $response = $this->get(route('admin.index'));
        $response->assertStatus(200);
     }

     public function test_page_simulasi()
     {
        $response = $this->get(route('admin.simulasi'));
        $response->assertStatus(200);
     }

     public function test_function_tahun_ajaran()
     {
         // Simulate a request to create a new tahun_ajaran record
         $response = $this->post(route('admin.simulasi_tambah.tahun_ajaran_store'), [
            'tahun' => '9099',
            'semester' => 'Ganjil', 
            // Include other fields as needed
        ]);

        // Assert that the response is successful (e.g., a redirect)
        $response->assertStatus(302);

     
        // Clean up: Delete the created tahun_ajaran record
        tahun_ajaran::where('tahun', '9099')->delete();
     }

     public function test_function_activate_tahun_ajaran()
     {
        # only get the route because it will impact the database (delete) 
        # if we create the test case

        $response = $this->get(route('admin.simulasi'));
        $response->assertStatus(200);
     }

     public function test_page_add_pegawai()
     {
        $response = $this->get(route('admin.register_pegawai'));
        $response->assertStatus(200);
     }

     public function test_function_add_pegawai()
     {
        // Generate fake data for registration
        $fakeUserData = [
            'name' => 'register pegawai',
            'email' => 'registerpegawai@example.com',
            'fakultas' => 'Fakultas Teknik', 
            'password' => 'password123', 
        ];

        // Simulate a request to register a new user
        $response = $this->post(route('admin.register_pegawai_store'), $fakeUserData);

        // Assert that the response is successful (e.g., a redirect)
        $response->assertStatus(302);

        // Assert that the new user data exists in the database
        $this->assertDatabaseHas('users', [
            'email' => $fakeUserData['email'],
        ]);

        // Clean up: Delete the created user
        User::where('email', $fakeUserData['email'])->delete();
    
     }


}
