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
use App\Models\history;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;

class PegawaiController extends Controller
{
    public function index(){

        $all_masuk = history::where('status','Sedang Diperiksa')->where('fakultas', auth()->user()->fakultas)->count();
        $all_diproses = history::where('status','Disetujui')->where('fakultas', auth()->user()->fakultas)->count();
        $all_selesai = history::where('status','Selesai')->where('fakultas', auth()->user()->fakultas)->count();
        $all_ditolak = history::where('status','Ditolak')->where('fakultas', auth()->user()->fakultas)->count();

        $this_month_masuk = history::where('status', 'Sedang Diperiksa')->where('fakultas', auth()->user()->fakultas)->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month)->count();
        $this_month_diproses = history::where('status', 'Disetujui')->where('fakultas', auth()->user()->fakultas)->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month)->count();
        $this_month_selesai = history::where('status', 'Selesai')->where('fakultas', auth()->user()->fakultas)->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month)->count();
        $this_month_ditolak = history::where('status', 'Ditolak')->where('fakultas', auth()->user()->fakultas)->whereYear('updated_at', Carbon::now()->year)->whereMonth('updated_at', Carbon::now()->month)->count();


        return view('pegawai.index',[
            'title' => 'Pegawai Dashboard',
            'dalam_proses' => User::where('status', 'Disetujui')->where('fakultas', auth()->user()->fakultas)->count(),
            'all_masuk' => $all_masuk,
            'all_diproses' => $all_diproses,
            'all_selesai' => $all_selesai,
            'all_ditolak' => $all_ditolak,
            'this_month_masuk' => $this_month_masuk,
            'this_month_diproses' => $this_month_diproses,
            'this_month_selesai' => $this_month_selesai,
            'this_month_ditolak' => $this_month_ditolak,
            

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
        elseif(auth()->user()->fakultas == 'Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian'){$namafakultas = 'Fakultas Matematika Ilmu Pengetahuan Alam Dan Kebumian';}
        elseif(auth()->user()->fakultas == 'Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat'){$namafakultas = 'Fakultas Ilmu Keolahragaan Dan Kesejahteraan Masyarakat';}
        elseif(auth()->user()->fakultas == 'Fakultas Teknik'){$namafakultas = 'Fakultas Teknik';}
        elseif(auth()->user()->fakultas == 'Fakultas Ekonomi Dan Bisnis'){$namafakultas = 'Fakultas Ekonomi Dan Bisnis';}
        elseif(auth()->user()->fakultas == 'Fakultas Ilmu Sosial Dan Hukum'){$namafakultas = 'Fakultas Ilmu Sosial Dan Hukum';}
        elseif(auth()->user()->fakultas == 'Fakultas Bahasa Dan Seni'){$namafakultas = 'Fakultas Bahasa Dan Seni';}
        

        return view('pegawai.semua_dosen',[
           'title' => 'Semua Dosen Fakultas',
           'pangkat' => pangkat::all(), 
           'fip' => jurusan_prodi::whereIn('id',[1,2,3,4,5,6])->get(),
           'fmipa' => jurusan_prodi::whereIn('id',[7,8,9,10,11,12,13,14])->get(),
           'fik' => jurusan_prodi::whereIn('id',[15,16,17,18])->get(),
           'fatek' => jurusan_prodi::whereIn('id',[19,20,21,22,23,24,25,26,27,28,29,30])->get(),
           'fekon' => jurusan_prodi::whereIn('id',[31,32,33,34,35])->get(),
           'fis' => jurusan_prodi::whereIn('id',[36,37,38,39,40,41,42,43])->get(),
           'fbs' => jurusan_prodi::whereIn('id',[44,45,46,47,48,49,50,51])->get(),
           'all_dosen' => User::where('fakultas', $namafakultas )->where('level', 'dosen')->get(),
        ]);
    }

    public function permintaan_kenaikan_pangkat(){

        return view('pegawai.permintaan_kenaikan_pangkat',[
            'title' => 'Pegawai | Permintaan Kenaikan Pangkat',
            'permintaan_kenaikan' => User::where('status', 'Permintaan Kenaikan Pangkat Reguler')
                                        ->orderBy('updated_at', 'ASC')
                                        ->get(),
        ]);

    }

    public function permintaan_kenaikan_pangkat_store(Request $request){

    }

    public function semua_dosen_store(Request $request){
        $validator = Validator::make($request->all(),[ 
            'name' => 'required|max:255|string',
            'email' => 'required|email|unique:users,email',
            // 'jurusan_prodi' => 'required',
            'pangkat_id' => 'required|exists:pangkats,id',
            'jurusan_prodi' => 'required|exists:jurusan_prodis,nama',
            'nip' => 'required|numeric|digits_between:18,18|unique:users,nip',
            'nidn' => 'required|numeric|digits_between:10,10|unique:users,nidn',

         ],[
            'pangkat_id.exists' => 'Golongan Belum dipilih',
            'jurusan_prodi.exists' => 'Belum Memilih Program Studi',
            'email.unique' => 'Email Sudah Terdaftar',
            'nip.unique' => 'NIP Sudah Terdaftar',
            'nidn.unique' => 'NIDN Sudah Terdaftar',
            'nip.required' => 'NIP harus diisi',
            'nip.digits_between' => 'NIP Harus 18 angka',
            'nip.numeric' => 'NIP Hanya berupa angka',
            'nidn.numeric' => 'NIDN Hanya berupa angka',
            'nidn.required' => 'NIDN harus diisi',
            'nidn.digits_between' => 'NIDN harus 10 angka',
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

        $newUser = User::create($validatedData);

        $user = request();
        // Create password and send to their email
            $plain = Str::random(5); 
            $password = bcrypt($plain);

            // dd($user->email);
            User::where('id', $newUser->id )->update(['password' => $password]);

            // preparing for sending email
                $email_to = $user->email;
                $data = [
                            'title' => 'DIHARAPKAN UNTUK SEGERA MENGUBAH PASSWORD ANDA !!!',
                            'p1' => 'Akun anda berhasil dibuat' , 
                            'p2' =>  'Anda Sudah Bisa Login Ke Website Kenaikan Pangkat Dosen Dengan Menggunakan Akun berikut Ini',
                            'credentials' =>  'Email = '.$user->email.' 
                                                Password = '.$plain.' 
                                                ',
                            'url' => route('login'),
                        ];
                
                // Send to Email
                Mail::to($email_to)->send(new UbahStatusKenaikanPangkat($data));

                // User doesn't have a record, so create a new one with passwords users
                $newData['status'] = null; 
                $newData['user_id'] = $newUser->id; 
            status_kenaikan_pangkat::create($newData);

        
        Alert::success('Data Berhasil Ditambahkan');
        return back();
    }

    public function ubah_status_kenaikan_pangkat(User $user){

        $naik_ke_pangkat = $user->pangkat_id + 1;
        // $user->pangkat_id == 1;

        return view('pegawai.ubah_status_kenaikan_pangkat',[
            'title' => 'Pegawai | Ubah Status',
            'user' => $user->load('pangkat'),
            'status_kenaikan_pangkat' => status_kenaikan_pangkat::where('user_id', $user->id)->first(),
            // 'naik_ke' => status_kenaikan_pangkat,
            'golongan' => pangkat::all(),
            'naik_ke_pangkat' => pangkat::where('id', $naik_ke_pangkat)->first(),
        ]);
    }

    public function ubah_status_kenaikan_pangkat_store(Request $request , User $user){
        $validator = Validator::make($request->all(),[ 
            'golongan' => 'nullable',
        ],[
            // 'golongan.exists' => 'Golongan Belum dipilih'
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
        // dd($request);
        $validator = Validator::make($request->all(),[ 
            'tanggapan' => 'required|',
        ],[
            'tanggapan.required' => 'Tanggapan harus diisi'
        ]);

        // validasi check 
        $check_berkas_ditolak = $request->validate([
            'check_kartu_pegawai_nip_baru_bkn' => 'nullable',
            'check_sk_cpns' => 'nullable',
            'check_sk_pangkat_terakhir' => 'nullable',
            'check_sk_jabfung_terakhir_dan_pak' => 'nullable',
            'check_ppk_dan_skp' => 'nullable',
            'check_ijazah_terakhir' => 'nullable',
            'check_sk_tugas_belajar_atau_surat_izin_studi' => 'nullable',
            'check_keterangan_membina_mata_kuliah_dari_jurusan' => 'nullable',
            'check_surat_pernyataan_setiap_bidang_tridharma' => 'nullable',
        ]);
        

        if ($validator->fails()) {
           Alert::error($validator->errors()->all()[0]);
           return redirect()->back()->withErrors($validator)->withInput();
       }
        // Validasi
        $validatedData = $validator->validated();
        $validatedData['status'] = 'Ditolak';
        
        User::where('id',$user->id)->update($validatedData);
        berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->update($check_berkas_ditolak);

        //preparing history
        $data_history = [
            'status' => 'Ditolak',
            'tanggapan' => $request->tanggapan,
            'user_id' => null,
        ];

        // Buat history
        history::where('user_id', $user->id)->update($data_history);

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

    public function pengajuan_selesai_store(User $user, Request $request){
        // dd($request);
        $validator = Validator::make($request->all(),[ 
            'status' => 'nullable',
         ]);

         if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi
        $validatedData = $validator->validated();

        $validatedData['pangkat_id'] = $user->pangkat_id + 1;
        $validatedData['tanggapan'] = null;
        $validatedData['status'] = null;

        User::where('id', $user->id)->update($validatedData);
        // dd($validatedData);


        status_kenaikan_pangkat::where('user_id', $user->id)->update(['status' => null]);

          //preparing history
          $data_history = [
            'user_id' =>null,
            'status' => 'Selesai',
            'pangkat_sekarang' => $user->pangkat_id + 1,
            'pangkat_berikut' => null,
        ];

        // Buat history
        history::where('user_id', $user->id)->update($data_history);

        $terbaru = pangkat::where('id', $validatedData['pangkat_id'])->first();
        $data = [
            'title' => 'Selamat Anda Sudah Naik Pangkat',
            'open' => 'Pangkat Sekarang Yaitu '. $terbaru->jabatan_fungsional.', '.$terbaru->pangkat.', '. $terbaru->golongan, 
            'close' =>  'Silahkan cek website untuk informasi lebih lanjut',
            'url' => route('login'),
        ];

        // Send to Email
        Mail::to($user->email)->send(new DosenNotifications($data));

        Alert::success('Pengajuan Berhasil Diselesaikan');
        return back();
    }
}
