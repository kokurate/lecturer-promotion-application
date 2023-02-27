<?php

namespace App\Http\Controllers;

use App\Mail\DosenNotifications;
use App\Models\jurusan_prodi;
use App\Models\pangkat;
use App\Models\status_kenaikan_pangkat;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use App\Mail\UbahStatusKenaikanPangkat;
use App\Models\berkas_kenaikan_pangkat_reguler;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;

class PegawaiController extends Controller
{
    public function index(){
        return view('pegawai.index',[
            'title' => 'Pegawai Dashboard'

        ]);
    }

    public function semua_dosen(){

        // if(auth()->user()->fakultas == 'Fakultas Ilmu Pendidikan'){
        //     $fakultas_fip = jurusan_prodi::whereIn('id',[1,2,3,4,5,6])->get();
        // }

        // $data = jurusan_prodi::whereIn('id',[7,8,9,10,11,12,13,14])->get();
        // dd($data);
        // $login = auth()->user()->fakultas
        if(auth()->user()->fakultas == 'Fakultas Ilmu Pendidikan'){$namafakultas = 'Fakultas Ilmu Pendidikan';}
        elseif(auth()->user()->fakultas == 'Fakultas Matematika Dan Ilmu Pengetahuan Alam'){$namafakultas = 'Fakultas Matematika Dan Ilmu Pengetahuan Alam';}
        elseif(auth()->user()->fakultas == 'Fakultas Ilmu Keolahragaan'){$namafakultas = 'Fakultas Ilmu Keolahragaan';}
        elseif(auth()->user()->fakultas == 'Fakultas Teknik'){$namafakultas = 'Fakultas Teknik';}
        elseif(auth()->user()->fakultas == 'Fakultas Ekonomi'){$namafakultas = 'Fakultas Ekonomi';}
        elseif(auth()->user()->fakultas == 'Fakultas Ilmu Sosial'){$namafakultas = 'Fakultas Ilmu Sosial';}
        elseif(auth()->user()->fakultas == 'Fakultas Bahasa Dan Seni'){$namafakultas = 'Fakultas Bahasa Dan Seni';}
        

        return view('pegawai.semua_dosen',[
           'title' => 'Semua Dosen Fakultas',
           'pangkat' => pangkat::all(), 
           'fip' => jurusan_prodi::whereIn('id',[1,2,3,4,5,6])->get(),
           'fmipa' => jurusan_prodi::whereIn('id',[7,8,9,10,11,12,13,14])->get(),
           'fik' => jurusan_prodi::whereIn('id',[15,16,17,18])->get(),
           'fatek' => jurusan_prodi::whereIn('id',[19,20,21,22,23,24,25,26,27,28,29])->get(),
           'fekon' => jurusan_prodi::whereIn('id',[30,31,32,33,34])->get(),
           'fis' => jurusan_prodi::whereIn('id',[35,36,37,38,39,40,41,42])->get(),
           'fbs' => jurusan_prodi::whereIn('id',[43,44,45,46,47,48,49,50])->get(),
           'all_dosen' => User::where('fakultas', $namafakultas )->where('level', 'dosen')->get(),
        ]);
    }

    public function semua_dosen_store(Request $request){
        $validator = Validator::make($request->all(),[ 
            'name' => 'required|max:255|string',
            'email' => 'required|email|unique:users,email',
            // 'jurusan_prodi' => 'required',
            'pangkat_id' => 'required|exists:pangkats,id',
            'jurusan_prodi' => 'required|exists:jurusan_prodis,nama'
         ],[
            'pangkat_id.exists' => 'Golongan Belum dipilih',
            'jurusan_prodi.exists' => 'Belum Memilih Program Studi',
            'email.unique' => 'Email Sudah Terdaftar',
         ]);

         if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi
        $validatedData = $validator->validated();
        // $validatedData['password'] = bcrypt(Str::random('5')); 
        $validatedData['level'] = 'dosen'; 
        $validatedData['fakultas'] = auth()->user()->fakultas; 

        User::create($validatedData);
        // dd($validatedData);
        
        Alert::success('Data Berhasil Ditambahkan');
        return back();
    }

    public function ubah_status_kenaikan_pangkat(User $user){

        return view('pegawai.ubah_status_kenaikan_pangkat',[
            'title' => 'Pegawai | Ubah Status',
            'user' => $user->load('pangkat'),
            'status_kenaikan_pangkat' => status_kenaikan_pangkat::where('user_id', $user->id)->first(),
            'golongan' => pangkat::all(),
        ]);
    }

    public function ubah_status_kenaikan_pangkat_store(Request $request , User $user){
        $validator = Validator::make($request->all(),[ 
            'golongan' => 'required|exists:pangkats,golongan',
        ],[
            'golongan.exists' => 'Golongan Belum dipilih'
        ]);

        if ($validator->fails()) {
           Alert::error($validator->errors()->all()[0]);
           return redirect()->back()->withErrors($validator)->withInput();
       }
        // Validasi
        $validatedData = $validator->validated();
        $validatedData['status'] = 'Tersedia'; 
        $validatedData['user_id'] = $user->id; 

        // status_kenaikan_pangkat::create($validatedData);
        
        // dd($validatedData);

        if (status_kenaikan_pangkat::where('user_id', $user->id)->doesntExist()) {
            
            $plain = Str::random(5); 
            $password = bcrypt($plain);

            User::where('id', $user->id)->update(['password' => $password]);

            // preparing for sending email
                $email_to = $user->email;
                $data = [
                            'title' => 'DIHARAPKAN UNTUK SEGERA MENGUBAH PASSWORD ANDA !!!',
                            'p1' => 'Status Kenaikan Pangkat Anda Diperbarui Menjadi '. $validatedData['status'] , 
                            'p2' =>  'Anda Sudah Bisa Mengajukan Kenaikan Pangkat Dengan Menggunakan Akun berikut Ini',
                            'credentials' =>  'Email = '.$user->email.' 
                                                Password = '.$plain.' 
                                                ',
                            'url' => route('login'),
                        ];
                
                // Send to Email
                Mail::to($email_to)->send(new UbahStatusKenaikanPangkat($data));

            // User doesn't have a record, so create a new one with passwords users
            status_kenaikan_pangkat::create($validatedData + ['user_id' => $user->id]);
        } else {

            // preparing for sending email
            $email_to = $user->email;
            $data = [
                        'title' => 'Selamat Datang',
                        'p1' => 'Status Kenaikan Pangkat Anda Diperbarui Menjadi '. $validatedData['status'] .' Untuk Naik Ke Golongan '. $validatedData['golongan'], 
                        'p2' =>  'Anda Sudah Bisa Mengajukan Kenaikan Pangkat Dengan Menggunakan Akun Anda',
                        'credentials' =>  'Email = '.$user->email,
                        'url' => route('login'),
                    ];
            
            // Send to Email
            Mail::to($email_to)->send(new UbahStatusKenaikanPangkat($data));

            // User already has a record, so update it
            status_kenaikan_pangkat::where('user_id', $user->id)->update($validatedData);
        }

        Alert::success('Status Kenaikan Pangkat Berhasil Diubah');
        return back();

    }

    public function pengajuan_masuk(){

        return view('pegawai.pengajuan_masuk',[
            'title' => 'Pegawai | Pengajuan Masuk',
            // yang duluan pengajuan di atas
            'masuk' => User::where(function ($query) {
                    $query->where('status', 'Sedang Diperiksa')
                        ->where('fakultas', auth()->user()->fakultas);})
                    ->orWhere('status', 'Disanggah')
                    ->orderBy('updated_at', 'ASC')
                    ->get(),
        ]);
    }

    public function pengajuan_masuk_user(User $user){
        return view('pegawai.pengajuan_masuk_user',[
            'title' => 'Pengajuan '.$user->name ,
            'user' => $user
        ]);
    }

    public function pengajuan_masuk_detail(User $user){
        // dd('Controller method called!', $email);
        // $user = User::where('email', $email)->firstOrFail();
        $berkas = berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->firstOrFail()->load('user');

    return view('pegawai.pengajuan_masuk_detail',[
        'title' => 'Detail Pengajuan',
        'berkas' => $berkas,
    ]);
    }

    public function pengajuan_masuk_detail_tolak_store(User $user, Request $request){
        $validator = Validator::make($request->all(),[ 
            'tanggapan' => 'required|',
        ],[
            'tanggapan.required' => 'Tanggapan harus diisi'
        ]);

        if ($validator->fails()) {
           Alert::error($validator->errors()->all()[0]);
           return redirect()->back()->withErrors($validator)->withInput();
       }
        // Validasi
        $validatedData = $validator->validated();
        $validatedData['status'] = 'Ditolak';
        
        User::where('id',$user->id)->update($validatedData);

        $data = [
            'title' => 'Notifikasi Dosen',
            'open' => 'Status Kenaikan Pangkat Anda Diperbarui Menjadi '. $validatedData['status'], 
            'close' =>  'Silahkan cek website untuk informasi lebih lanjut',
            'url' => route('login'),
        ];

        // Send to Email
        Mail::to($user->email)->send(new DosenNotifications($data));

        Alert::success('Berhasil','Pengajuan berhasil diupdate');
        return redirect()->route('pegawai.index');
    }

    public function pengajuan_dalam_proses(){
        return view('pegawai.dalam_proses',[
            'title' => 'Pegawai | Dalam Proses',
            // yang duluan di bawah
            'dalam_proses' => User::where(function ($query) {
                            $query->where('status', 'Disetujui')
                                ->where('fakultas', auth()->user()->fakultas);})
                            ->orWhere('status', 'Disanggah')
                            ->orderBy('updated_at', 'DESC')
                            ->get()->load('berkas_kenaikan_pangkat_reguler'),

        ]);
    }
}
