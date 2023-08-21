<?php

namespace App\Http\Controllers;

use App\Models\my_storage;
use App\Models\berkas_kenaikan_pangkat_reguler;
use App\Models\history;
use App\Models\kategori_pak;
use App\Models\pak_kegiatan_pendidikan_dan_pengajaran;
use App\Models\pak_kegiatan_penelitian;
use App\Models\pak_kegiatan_pengabdian_pada_masyarakat;
use App\Models\pak_kegiatan_penunjang_tri_dharma_pt;
use App\Models\status_kenaikan_pangkat;
use App\Models\tahun_ajaran;
use App\Models\tipe_kegiatan_pendidikan_dan_pengajaran;
use App\Models\tipe_kegiatan_penelitian;
use App\Models\tipe_kegiatan_pengabdian_pada_masyarakat;
use App\Models\tipe_kegiatan_penunjang_tri_dharma_pt;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index(){
        return view('dosen.index',[
            'title' => 'Dosen Dashboard',
            'user' => auth()->user(),
        ]);
    }

    public function sudah_bisa_naik_pangkat_reguler(Request $request){

        // dd($request->all());
        $validator = Validator::make($request->all(),[ 
            'status' => 'required',
        ],[
            // 'golongan.exists' => 'Golongan Belum dipilih'
        ]);

        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi
        $validatedData = $validator->validated();
        $validatedData['status'] = 'Permintaan Kenaikan Pangkat Reguler'; 

        User::where('id', auth()->user()->id )->update($validatedData);

        status_kenaikan_pangkat::where('user_id', auth()->user()->id)->update(['status' => 'Permintaan Kenaikan Pangkat Reguler']);
    
        Alert::success('Permintaan Kenaikan Pangkat Berhasil');
        return back();
    }

    public function tambah_pangkat_reguler(User $user){
        return view('dosen.tambah_pangkat_reguler',[
            'title' => 'Unggah Berkas',
            'storage' => my_storage::where('user_id', auth()->user()->id)->whereNotNull('path')->get(),
            'user' => $user,
        ]);
    }

    public function tambah_pangkat_reguler_store(Request $request, User $user){
        $validator = Validator::make($request->all(),[
            'kartu_pegawai_nip_baru_bkn' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                $query->where('user_id', auth()->user()->id);})],
            'sk_cpns' =>                                    [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'sk_pangkat_terakhir' =>                                [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'sk_jabfung_terakhir_dan_pak' =>                                [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'ppk_dan_skp' =>                                [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'ijazah_terakhir' => 'nullable',
            'sk_tugas_belajar_atau_surat_izin_studi' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'keterangan_membina_mata_kuliah_dari_jurusan' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'surat_pernyataan_setiap_bidang_tridharma' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],

        ],[
            'kartu_pegawai_nip_baru_bkn.exists' => 'Belum Memilih File Kartu Pegawai & NIP Baru BKN',
            'sk_cpns.exists' => 'Belum Memilih File SK CPNS',
            'sk_pangkat_terakhir.exists' => 'Belum Memilih File SK Pangkat Terakhir',
            'sk_jabfung_terakhir_dan_pak.exists' => 'Belum Memilih File SK Jabatan Fungsional Terakhir dan PAK',
            'ppk_dan_skp.exists' => 'Belum Memilih File PPK dan SKP',
            'ijazah_terakhir.exists' => 'Belum Memilih File Ijazah',
            'sk_tugas_belajar_atau_surat_izin_studi.exists' => 'Belum Memilih File SK Tugas Belajar atau Surat Izin Studi',
            'keterangan_membina_mata_kuliah_dari_jurusan.exists' => 'Belum Memilih File Keterangan Membina Mata Kuliah dari Jurusan',
            'surat_pernyataan_setiap_bidang_tridharma.exists' => 'Belum Memilih File Surat Pernyataan Setiap Bidang Tridharma',
            
            // 'kartu_pegawai_nip_baru_bkn.in' => 'Pilih file anda',
        ]);    
        // Kalo error kase alert error
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi
        $validatedData = $validator->validated();
        $validatedData['user_id'] = $user->id;

        if($validatedData['ijazah_terakhir'] == 'null'){
            $validatedData['ijazah_terakhir'] =  'tidak-upload.pdf';
            // berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->update(['ijazah_terakhir', $validatedData['ijazah_terakhir']]);
        }

        // ubah status
        status_kenaikan_pangkat::where('user_id', $user->id)->update(['status' => 'Belum Tersedia']);

        

        // dd($validatedData);
        if (berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->doesntExist()) {
            // User doesn't have a record, so create a new one
            berkas_kenaikan_pangkat_reguler::create($validatedData + ['user_id' => $user->id]);
        } else {
            // User already has a record, so update it
            berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->update($validatedData);
        }

        User::where('id', $user->id)->update(['status' => 'Sedang Diperiksa']);

        //preparing history
        $data_history = [
            'user_id' => $user->id,
            'user_id_old' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip,
            'nidn' => $user->nidn,
            'fakultas' => $user->fakultas,
            'jurusan_prodi' => $user->jurusan_prodi,
            'status' => 'Sedang Diperiksa',
            'pangkat_sekarang' => $user->pangkat_id,
            'pangkat_berikut' => $user->pangkat_id + 1,
        ];

        // Buat history
        history::where('user_id', $user->id)->create($data_history);
   
        // Save the file path to the kartu_pegawai_nip_baru_bkn column of the berkas_kenaikan_pangkat_regulers table
        // $berkas = new berkas_kenaikan_pangkat_reguler();
        // $berkas->user_id = $user->id;
        // $berkas->kartu_pegawai_nip_baru_bkn = $validatedData['kartu_pegawai_nip_baru_bkn'];
        // $berkas->sk_cpns = $validatedData['sk_cpns'];
        // $berkas->sk_pangkat_terakhir = $validatedData['sk_pangkat_terakhir'];
        // $berkas->sk_jabfung_terakhir_dan_pak = $validatedData['sk_jabfung_terakhir_dan_pak'];
        // $berkas->ppk_dan_skp = $validatedData['ppk_dan_skp'];
        // $berkas->ijazah_terakhir = $validatedData['ijazah_terakhir'];
        // $berkas->sk_tugas_belajar_atau_surat_izin_studi = $validatedData['sk_tugas_belajar_atau_surat_izin_studi'];
        // $berkas->keterangan_membina_mata_kuliah_dari_jurusan = $validatedData['keterangan_membina_mata_kuliah_dari_jurusan'];
        // $berkas->surat_pernyataan_setiap_bidang_tridharma = $validatedData['surat_pernyataan_setiap_bidang_tridharma'];
        
        // $berkas->save();


        Alert::success('Berhasil','Pengajuan Kenaikan Pangkat Berhasil Dibuat');

        return redirect()->route('dosen.index');

    }

    public function storage(){
        return view('dosen.storage',[
            'title' => 'Dosen | Storage',
            'all_storage' => my_storage::where('user_id', auth()->user()->id)->get(),
        ]);
    }

    public function storage_store(Request $request,){
        
        $validator = Validator::make($request->all(),[
            'path' => 'required|max:1024|mimes:pdf',
            'nama' => 'required|max:255'
        ],[
            'path.required' => 'Tidak ada file yang diupload',
            'path.max' => 'Maksimal file 1 MB',
            'path.mimes' => 'File harus format pdf',
        ]);
        // Kalo error kase alert error
        // if($validator->fails()){
        //     Alert::error($validator->errors()->all()[0]);
        //     return back()->withErrors($validator)->withInput();
        // }

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Menambahkan Akun Dosen');
        }

        // Validasi
        $validatedData = $validator->validated();

        $namadosen = auth()->user()->name;
        $slug = Str::slug($namadosen);
        $namafileslug = Str::slug($request->input('nama'));
        // $custom_file_name = time().'-'.$namafileslug ->getCli();

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['filename'] = Str::random('50');
        $validatedData['path'] = $request->file('path')->storeAs('dosen/'. $slug, time().'-'.$namafileslug.'.pdf');

        // dd($validatedData);

        // Create Storage
        my_storage::create($validatedData);

        Alert::success('File Berhasil diupload');
        return redirect()->route('dosen.storage');
    }

    public function storage_destroy(my_storage $my_storage){
        if($my_storage->path){
            Storage::delete($my_storage->path);
        }
        my_storage::destroy($my_storage->id);

        Alert::success('File Berhasil dihapus');
        return back();

    }

    public function verify_nip_and_nidn(){
        return view('dosen.verifikasi_nip_dan_nidn',[
            'title' => 'Verifikasi'
        ]);
    }

    
    // public function verify_nip_and_nidn_store(Request $request){
        
    //     $validator = Validator::make($request->all(),[
    //         'nip' => 'required|numeric|digits_between:18,18',
    //         'nidn' => 'required|numeric|digits_between:10,10'
    //     ],[
    //         'nip.required' => 'NIP harus diisi',
    //         // 'nip.digits_between' => ':attribute harus minimal :min dan maksimal :max angka',
    //         'nip.digits_between' => 'NIP Harus 18 angka',
    //         'nip.numeric' => 'NIP Hanya berupa angka',
    //         'nidn.numeric' => 'NIDN Hanya berupa angka',
    //         'nidn.required' => 'NIDN harus diisi',
    //         // 'nidn.digits_between' => ':attribute harus minimal :min dan maksimal :max angka',
    //         'nidn.digits_between' => 'NIDN harus 10 angka',
    //     ]);
        

    //     if ($validator->fails()) {
    //         Alert::error($validator->errors()->all()[0]);
    //         return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Menambahkan Akun Dosen');
    //     }

    //     // Validasi
    //     $validatedData = $validator->validated();
    //     $user = auth()->user()->id;
    //     User::where('id', $user)->update($validatedData);

    //     Alert::success('Berhasil','NIP dan NIDN berhasil disimpan');
    //     return redirect()->route('dosen.index');
    //     // dd($validatedData);
    // }

    public function status_kenaikan_pangkat(){
        return view('dosen.status_kenaikan_pangkat',[
            'title' => 'Dosen | Status Kenaikan Pangkat',
        ]);
    }

    public function sanggah(){
        return view('dosen.sanggah',[
            'title' => 'Dosen | Sanggah',
            'storage' => my_storage::where('user_id', auth()->user()->id)->whereNotNull('path')->get(),
        ]);
    }

    public function sanggah_store(User $user, Request $request){
        // dd($request);
        $validator = Validator::make($request->all(),[
            'kartu_pegawai_nip_baru_bkn' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                $query->where('user_id', auth()->user()->id);})],
            'sk_cpns' =>                                    [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'sk_pangkat_terakhir' =>                                [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'sk_jabfung_terakhir_dan_pak' =>                                [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'ppk_dan_skp' =>                                [Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'ijazah_terakhir' => 'nullable',
            'sk_tugas_belajar_atau_surat_izin_studi' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'keterangan_membina_mata_kuliah_dari_jurusan' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
            'surat_pernyataan_setiap_bidang_tridharma' => [ Rule::exists('my_storages', 'path')->where(function ($query) {
                                                            $query->where('user_id', auth()->user()->id);})],
        ],[
            'kartu_pegawai_nip_baru_bkn.exists' => 'Belum Memilih File Kartu Pegawai & NIP Baru BKN',
            'sk_cpns.exists' => 'Belum Memilih File SK CPNS',
            'sk_pangkat_terakhir.exists' => 'Belum Memilih File SK Pangkat Terakhir',
            'sk_jabfung_terakhir_dan_pak.exists' => 'Belum Memilih File SK Jabatan Fungsional Terakhir dan PAK',
            'ppk_dan_skp.exists' => 'Belum Memilih File PPK dan SKP',
            'ijazah_terakhir.exists' => 'Belum Memilih File Ijazah',
            'sk_tugas_belajar_atau_surat_izin_studi.exists' => 'Belum Memilih File SK Tugas Belajar atau Surat Izin Studi',
            'keterangan_membina_mata_kuliah_dari_jurusan.exists' => 'Belum Memilih File Keterangan Membina Mata Kuliah dari Jurusan',
            'surat_pernyataan_setiap_bidang_tridharma.exists' => 'Belum Memilih File Surat Pernyataan Setiap Bidang Tridharma',
            
            // 'kartu_pegawai_nip_baru_bkn.in' => 'Pilih file anda',
        ]);    
        // Kalo error kase alert error
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi
        $validatedData = $validator->validated();
        $validatedData['user_id'] = $user->id;

        $validatedData['check_kartu_pegawai_nip_baru_bkn'] = 0;
        $validatedData['check_sk_cpns'] = 0;
        $validatedData['check_sk_pangkat_terakhir'] = 0;
        $validatedData['check_sk_jabfung_terakhir_dan_pak'] = 0;
        $validatedData['check_ppk_dan_skp'] = 0;
        $validatedData['check_ijazah_terakhir'] = 0;
        $validatedData['check_sk_tugas_belajar_atau_surat_izin_studi'] = 0;
        $validatedData['check_keterangan_membina_mata_kuliah_dari_jurusan'] = 0;
        $validatedData['check_surat_pernyataan_setiap_bidang_tridharma'] = 0;

        // ubah status
        status_kenaikan_pangkat::where('user_id', $user->id)->update(['status' => 'Belum Tersedia']);

        // dd($validatedData);
        if (berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->doesntExist()) {
            // User doesn't have a record, so create a new one
            berkas_kenaikan_pangkat_reguler::create($validatedData + ['user_id' => $user->id]);
        } else {
            // User already has a record, so update it
            berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->update($validatedData);
        }

        User::where('id', $user->id)->update(['status' => 'Sedang Diperiksa']);

          //preparing history
          $data_history = [
            'user_id' => $user->id,
            'user_id_old' => $user->id,
            'nama' => $user->name,
            'email' => $user->email,
            'nip' => $user->nip,
            'nidn' => $user->nidn,
            'fakultas' => $user->fakultas,
            'jurusan_prodi' => $user->jurusan_prodi,
            'status' => 'Sedang Diperiksa',
            'pangkat_sekarang' => $user->pangkat_id,
            'pangkat_berikut' => $user->pangkat_id + 1,
        ];

        // Buat history
        history::where('user_id', $user->id)->create($data_history);
   

        Alert::success('Berhasil','Pengajuan Kenaikan Pangkat Berhasil Dibuat');

        return redirect()->route('dosen.index');
    }

    public function simulasi(){

        $user = Auth::user();

        // Banyaknya
        $banyaknya = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->count('id') + 
                    pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->count('id') +
                    pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->count('id') +
                    pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->count('id') ;
       
        $total_kredit = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->sum('angka_kredit') +
                        pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->sum('angka_kredit') +
                        pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->sum('angka_kredit') +
                        pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->sum('angka_kredit') ;

        return view('dosen.simulasi.index',[
            'title' => 'Dosen | Simulasi',
            // 'kategori_pak' => kategori_pak::all(),
            // 'kategori_pak' => kategori_pak::withCount(['pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
            //         $query->where('user_id', $user->id);
            //     }])
            //     ->with(['pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
            //         $query->where('user_id', $user->id);
            //     }])
            //     ->get(),
            'kategori_pak' => kategori_pak::withCount([
                'pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'pak_kegiatan_penelitian' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'pak_kegiatan_pengabdian_pada_masyarakat' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'pak_kegiatan_penunjang_tri_dharma_pt' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            ->with([
                'pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'pak_kegiatan_penelitian' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'pak_kegiatan_pengabdian_pada_masyarakat' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                },
                'pak_kegiatan_penunjang_tri_dharma_pt' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            ])
            // ->whereHas('tahun_ajaran', function ($query) {
            //     $query->where('now', 1);
            // })
            ->get(),
            'total_kegiatan' => $banyaknya,
            'jumlah_kredit' => $total_kredit,                                 
        ]);

    }

 


    # Pendidikan dan pengajaran
    ############################################
    public function pendidikan_dan_pengajaran(){
        //    with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->
        return view('dosen.simulasi.pendidikan_dan_pengajaran.index',[
            'title' => 'Simulasi Pendidikan dan Pengajaran',
            'all' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->with('tahun_ajaran')->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                                                                ->whereHas('tahun_ajaran', function ($query) {
                                                                                                    $query->where('now', true);
                                                                                                })
                                                                                ->orderBy('id', 'DESC')
                                                                                ->get(),
            'tahun_ajaran' => tahun_ajaran::where('now', true)->get(),
            'total_kredit' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)->sum('angka_kredit'),
            'pendidikan_formal'=> pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                    ->where('tipe_kegiatan','Mengikuti Pendidikan Formal')->count(),
            'diklat_pra_jabatan' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                    ->where('tipe_kegiatan','Mengikuti Diklat Pra-Jabatan')->count(),
            'total_sks' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                    ->where('tipe_kegiatan', 'Melaksanakan Perkuliahan')->where('komponen_kegiatan', 'Mengajar')->sum('sks'),
            'pendidikan_dokter_klinis' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where(function ($query) {
                                        $query->where('kode', 'II.A.3.a')
                                            ->orWhere('kode', 'II.A.3.b')
                                            ->orWhere('kode', 'II.A.3.c')
                                            ->orWhere('kode', 'II.A.3.d')
                                            ->orWhere('kode', 'II.A.3.e');
                                    })->sum('angka_kredit'),      
        'membimbing_seminar_mahasiswa' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                    ->where('tipe_kegiatan','Membimbing Seminar Mahasiswa (Setiap Mahasiswa)')->count(),
        'membimbing_kkn_dst' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                    ->where('tipe_kegiatan','Membimbing KKN, Praktik Kerja Nyata, Praktik Kerja Lapangan')->count(),
        // Pembimbing 1 Count
            'p1_disertasi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                        ->where('kode','II.D.1.a')->count(),
            'p1_tesis' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.D.1.b')->count(),
            'p1_skripsi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.D.1.c')->count(),
            'p1_laporan_akhir_studi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                            ->where('kode','II.D.1.d')->count(),
        // Pembimbing 2 Count
            'p2_disertasi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                        ->where('kode','II.D.2.a')->count(),
            'p2_tesis' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.D.2.b')->count(),
            'p2_skripsi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.D.2.c')->count(),
            'p2_laporan_akhir_studi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.D.2.d')->count(),
                                                

            'ketua_penguji' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.E.1')->count(),
            'anggota_penguji' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.E.2')->count(),
            'membina_kegiatan_mahasiswa' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.F')->count(),
            'mengembangkan_program_kuliah' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.G')->count(),
            'buku_ajar' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.H.1')->count(),
            'diklat_modul' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.H.2')->count(),
            'orasi_ilmiah' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.I')->count(),
            'menduduki_jabatan' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where(function ($query) { $query ->where('kode', 'II.J.1')
                                                                                ->orWhere('kode','II.J.2') 
                                                                                ->orWhere('kode','II.J.3') 
                                                                                ->orWhere('kode','II.J.4') 
                                                                                ->orWhere('kode','II.J.5') 
                                                                                ->orWhere('kode','II.J.6') 
                                                                                ->orWhere('kode','II.J.7') 
                                                                                ->orWhere('kode','II.J.8'); 
                                                                            })->count(),
            'pembimbing_pencangkokan' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.K.1')->count(),
            'pembimbing_reguler' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.K.2')->count(),
            'detasering_luar_instansi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.L.1')->count(),
            'pencangkokan_luar_instansi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.L.2')->count(),
            'pengembangan_diri' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where(function ($query) { $query ->where('kode', 'II.M.1')
                                                                                ->orWhere('kode','II.M.2') 
                                                                                ->orWhere('kode','II.M.3') 
                                                                                ->orWhere('kode','II.M.4') 
                                                                                ->orWhere('kode','II.M.5') 
                                                                                ->orWhere('kode','II.M.6') 
                                                                                ->orWhere('kode','II.M.7'); 
                                                                            })->count(),


        ]);
    }

    public function pendidikan_dan_pengajaran_tambah(){

        $tipe_kegiatan = tipe_kegiatan_pendidikan_dan_pengajaran::all();


        return view('dosen.simulasi.pendidikan_dan_pengajaran.tambah',[
        'title' => 'Tambah Pendidikan dan Pengajaran',
        'tipe_kegiatan' => $tipe_kegiatan,
        't_a' => tahun_ajaran::where('now', 1)->value('tahun'),
        'semester' => tahun_ajaran::where('now', 1)->value('semester'),
        'tahun_ajaran_hidden' =>  tahun_ajaran::where('now',1)->first(),
        'sks_now' => pak_kegiatan_pendidikan_dan_pengajaran::where('user_id',auth()->user()->id)->sum('sks'),
        'dokter_klinis_now' => pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where(function ($query) {
                                                                                                            $query->where('kode', 'II.A.3.a')
                                                                                                                ->orWhere('kode', 'II.A.3.b')
                                                                                                                ->orWhere('kode', 'II.A.3.c')
                                                                                                                ->orWhere('kode', 'II.A.3.d')
                                                                                                                ->orWhere('kode', 'II.A.3.e');
                                                                                                        })->sum('angka_kredit'),
      

    ]);
    }

    public function pendidikan_dan_pengajaran_destroy($slug){
        

        // Retrieve the record from the table based on the slug
        $record = pak_kegiatan_pendidikan_dan_pengajaran::where('slug', $slug)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        if($record->bukti){
            Storage::delete($record->bukti);
        }
        

        pak_kegiatan_pendidikan_dan_pengajaran::destroy($record->id);

        Alert::success('Sukses','File Berhasil dihapus');
        return redirect()->route('pendidikan-dan-pengajaran');
    }

    public function pendidikan_dan_pengajaran_tambah_store(Request $request){

        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'kegiatan' => 'regex:/^[a-zA-Z0-9\s]+$/|required|max:255|',
            'tipe_kegiatan' => 'required|max:255',
            'tahun_ajaran_id' => 'required' ,
            'bukti' => 'required|max:1024|mimes:pdf',
        ],[
            'kegiatan.required' => 'Nama Kegiatan Harus diisi',
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'kegiatan.regex' => 'Nama Kegiatan hanya boleh mengandung huruf dan spasi',
            'tipe_kegiatan.required' => 'Tipe kegiatan harus diisi',
            'tipe_kegiatan.max' => 'Maksimal 255 karakter',
            'tahun_ajaran.required' => 'Tahun Ajaran harus diisi',
            'bukti.required' => 'Bukti harus diupload',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
        ]);
    
        
        if($request->input('tipe_kegiatan') == 'default'){
            $errorMessage = 'Tipe Kegiatan Harus Diisi';
            Alert::error($errorMessage);
            return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        }
    
        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Menambahkan Kegiatan');
        }



        // simpan data
        $validatedData = $validator->validated();

            // =============================== 1
                // Cek jenis pendidikan formal yang dipilih
                if ($validatedData['tipe_kegiatan'] === 'Mengikuti Pendidikan Formal') {
                    $jenisPendidikan = $request->input('jenis_pendidikan');
                    $user_id = auth()->user()->id;

                    // Validasi jenis pendidikan formal yang dipilih
                    $validator = Validator::make($request->all(), [
                        'jenis_pendidikan' => 'required|in:doktor,magister',
                    ], [
                        'jenis_pendidikan.required' => 'Jenis pendidikan formal harus dipilih',
                        'jenis_pendidikan.in' => 'Jenis pendidikan formal yang dipilih tidak valid',
                    ]);

                    if ($validator->fails()) {
                        Alert::error($validator->errors()->first());
                        return redirect()->back()->withInput()->with('error', 'Gagal Menambahkan Kegiatan');
                    }

                        // // Mengecek apakah pengguna sudah memasukkan jenis pendidikan doktor atau magister sebelumnya
                        // $existingPendidikan = pak_kegiatan_pendidikan_dan_pengajaran::where(function ($query) use ($jenisPendidikan, $user_id) {
                        //     $query->where('jenis_pendidikan', 'doktor')
                        //         ->orWhere('jenis_pendidikan', 'magister');
                        // })
                        //     ->where('user_id', $user_id)->where('kategori_pak_id', 1)
                        //     ->exists();

                        // if ($existingPendidikan) {
                        //     // Menampilkan pesan error jika pengguna sudah memasukkan jenis pendidikan doktor atau magister sebelumnya
                        //     Alert::error('Anda sudah memasukkan jenis pendidikan doktor atau magister sebelumnya');
                        //     return redirect()->back()->withInput()->withErrors(['jenis_pendidikan' => 'Anda sudah memasukkan jenis pendidikan doktor atau magister sebelumnya'])->with('error', 'Gagal Menambahkan Kegiatan');
                        // }

                    // Simpan jenis pendidikan formal yang dipilih
                    $validatedData['jenis_pendidikan'] = $jenisPendidikan;

                    // Simpan angka kredit berdasarkan jenis pendidikan formal yang dipilih
                    if ($jenisPendidikan === 'doktor') {
                        $validatedData['kode'] = 'I.A.1.a';
                        $validatedData['angka_kredit'] = 200;
                        $validatedData['komponen_kegiatan'] = 'Doktor/sederajat';
                    } elseif ($jenisPendidikan === 'magister') {
                        $validatedData['kode'] = 'I.A.1.b';
                        $validatedData['angka_kredit'] = 150;
                        $validatedData['komponen_kegiatan'] = 'Magister/sederajat';
                    }
                }


            // ==================================== 2
            // Diklat Pra Jabatan
                if($request->input('tipe_kegiatan') == 'Mengikuti Diklat Pra-Jabatan'){

                    // Mengecek apakah pengguna sudah memasukkan diklat pra jabatan
                    // $existingDiklat = pak_kegiatan_pendidikan_dan_pengajaran::where('tipe_kegiatan', $request->input('tipe_kegiatan'))
                    //     ->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                    //     ->exists();

                    // if ($existingDiklat) {
                    //     // Menampilkan pesan error jika pengguna sudah memasukkan Diklat pra jabatan sebelumnya
                    //     Alert::error('Anda sudah memasukkan Diklat Pra-Jabatan sebelumnya');
                    //     return redirect()->back()->withInput()->withErrors(['tipe_kegiatan' => 'Anda sudah memasukkan Diklat Pra-Jabatan sebelumnya'])->with('error', 'Gagal Menambahkan Kegiatan');
                    // }

                    // jika aman sisipkan angka kredit 
                    $validatedData['angka_kredit'] = 3;
                    $validatedData['kode'] = 'I.A.2';
                    $validatedData['komponen_kegiatan'] = 'Mengikuti Diklat Prajabatan gelombang III';
                }


            
            
            // error kalo ada dua input bersamaan pada dokter klinis dan perkuliahan
                if($request->input('perkuliahan') != NULL && $request->input('dokter_klinis') != NULL ){
                    Alert::error('Anda Memasukkan SKS dan Kegiatan pelaksanaan pendidikan untuk pendidikan dokter klinis secara bersamaan ');
                    return redirect()->back()->withErrors(['perkuliahan' => 'Anda Memasukkan SKS dan Kegiatan pelaksanaan pendidikan untuk pendidikan dokter klinis secara bersamaan'])->withInput();

                }

            // ================================== 3
            // Melaksanakan perkuliahan
            // sks check
                if($request->input('perkuliahan') != NULL && $request->input('dokter_klinis') == NULL ){
                    
                    $asisten_ahli = User::where('id', auth()->user()->id)->whereIn('pangkat_id', [1, 2])->first();
                    $lektor_dst = User::where('id', auth()->user()->id)->whereIn('pangkat_id', [3,4,5,6,7,8,9])->first();
                    //validasi pangkat asisten ahli
                    if($asisten_ahli){
                         // Validasi input dari form
                         if($request->tipe_kegiatan != NULL && $request->input('perkuliahan') == NULL || $request->input('perkuliahan') != NULL){

                             $validator = Validator::make($request->all(), [
                                    'perkuliahan' => 'numeric|required',
                                ],[
                                     'perkuliahan.required' => 'SKS harus diisi',
                                     'perkuliahan.numeric' => 'SKS harus berbentuk angka'
                                ]);
    
                                // error
                                if ($validator->fails()) {
                                    Alert::error($validator->errors()->first());
                                    return redirect()->back()->withInput()->with('error', 'Gagal Menambahkan Kegiatan');
                                }
                         }

                            // Mendapatkan nilai SKS sekarang
                            // $sksNow = $request->sks_now;
                            $sksNow = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id',auth()->user()->id)->sum('sks_reset');

                            // Mendapatkan jumlah SKS yang dimasukkan melalui request
                            $sksRequest = $request->perkuliahan;

                            // Validasi jumlah SKS
                            $totalSks = $sksNow + $sksRequest;

                             if ($totalSks <= 10) {
                                // Hitung jumlah SKS
                                $nilaiSks = $sksRequest * 0.5;
                                $validatedData['kode'] = 'II.A.1.a';
                                $validatedData['sks_reset'] = $sksRequest;
                            }elseif($totalSks > 10 && $totalSks <= 12){

                                // Hitung jumlah SKS
                                $sks10 = 10 - $sksNow; // SKS yang dibutuhkan untuk mencapai 10
                                $sksLain = $sksRequest - $sks10; // Sisa SKS di atas 10
                                $nilaiSks = ($sks10 * 0.5) + ($sksLain * 0.25);
                                $validatedData['kode'] = 'II.A.1.b';
                                $validatedData['sks_reset'] = $sksRequest;

                            }elseif($totalSks > 11 && $totalSks < 13){

                                $nilaiSks = $sksRequest * 0.25;
                                $validatedData['kode'] = 'II.A.1.b';
                                $validatedData['sks_reset'] = $sksRequest;

                            }elseif($totalSks > 12 && $totalSks <= 23){
                                

                                    if($sksNow < 11){
                                        $sksUnder10 = 10 - $sksNow; // 1 sks untuk mencapai 10 
                                        $sksBetween= 12 - $sksNow -  $sksUnder10; // 12 - 9 = 3 - 1 = 2
                                        $sks13 = 12 - $sksNow; // 12 - 9 = 3 
                                        $sks_diatas_13 = $sksRequest - $sks13; // sisa sks di atas 13 | 5 - 3 = 2  
                                        $nilaiSks =  ($sksUnder10 * 0.5) + ($sksBetween * 0.25) + ($sks_diatas_13 * 0.5);  
                                     
                                    }else{
                                        // hitung jumlah sks 
                                        $sks13= 12 - $sksNow; // Sks yang dibutuhkan untuk mencapai 13
                                        $sks_diatas_13 = $sksRequest - $sks13; // sisa sks di atas 13
                                        $nilaiSks = ($sks13 * 0.25) + ($sks_diatas_13 * 0.5);
                                    }

                                    // // hitung jumlah sks 
                                    // $sks13= 12 - $sksNow; // Sks yang dibutuhkan untuk mencapai 13
                                    // $sks_diatas_13 = $sksRequest - $sks13; // sisa sks di atas 13
                                    // $nilaiSks = ($sks13 * 0.25) + ($sks_diatas_13 * 0.5);  
                                    // $validatedData['kode'] = 'II.A.1.b';
                               

                                // Mengambil nilai 'sks_reset' untuk user saat ini
                                pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->pluck('sks_reset')->first();

                                // Memperbarui semua nilai 'sks_reset' untuk user saat ini menjadi 0
                                pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->update(['sks_reset' => 0]);

                                $validatedData['sks_reset'] = $sks_diatas_13;
                                $validatedData['kode'] = 'II.A.1.a';
                            }
                           


                            // Set nilai angka kredit sesuai dengan nilai SKS
                            
                            $validatedData['angka_kredit'] = $nilaiSks;
                            $validatedData['sks'] = $sksRequest;
                            $validatedData['komponen_kegiatan'] = 'Mengajar';
                           

                    }

                    // validasi pangkat lektor dst
                    if($lektor_dst){

                        // Validasi input dari form
                        if($request->tipe_kegiatan != NULL && $request->input('perkuliahan') == NULL || $request->input('perkuliahan') != NULL){

                                $validator = Validator::make($request->all(), [
                                    'perkuliahan' => 'numeric|required',
                                ],[
                                        'perkuliahan.required' => 'SKS harus diisi',
                                        'perkuliahan.numeric' => 'SKS harus berbentuk angka'
                                ]);
    
                                // error
                                if ($validator->fails()) {
                                    Alert::error($validator->errors()->first());
                                    return redirect()->back()->withInput();
                                }
                            }

                            // Mendapatkan nilai SKS sekarang
                             $sksNow = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id',auth()->user()->id)->sum('sks_reset');

                            // Mendapatkan jumlah SKS yang dimasukkan melalui request
                            $sksRequest = $request->perkuliahan;

                            // Validasi jumlah SKS
                            $totalSks = $sksNow + $sksRequest;

                             if ($totalSks <= 10) {
                                // Hitung jumlah SKS
                                $nilaiSks = $sksRequest * 1;
                                $validatedData['kode'] = 'II.A.2.a';
                                $validatedData['sks_reset'] = $sksRequest;
                            }elseif($totalSks > 10 && $totalSks <= 12){
                                
                                // Hitung jumlah SKS
                                $sks10 = 10 - $sksNow; // SKS yang dibutuhkan untuk mencapai 10
                                $sksLain = $sksRequest - $sks10; // Sisa SKS di atas 10
                                $nilaiSks = ($sks10 * 1) + ($sksLain * 0.5);
                                $validatedData['kode'] = 'II.A.2.a';
                                $validatedData['sks_reset'] = $sksRequest;

                            }elseif($totalSks > 11 && $totalSks < 13){

                                $nilaiSks = $sksRequest * 0.5;
                                $validatedData['kode'] = 'II.A.2.a';
                                $validatedData['sks_reset'] = $sksRequest;

                            }elseif($totalSks > 12 && $totalSks <= 23){
                                

                                    if($sksNow < 11){
                                        $sksUnder10 = 10 - $sksNow; // 1 sks untuk mencapai 10 
                                        $sksBetween= 12 - $sksNow -  $sksUnder10; // 12 - 9 = 3 - 1 = 2
                                        $sks13 = 12 - $sksNow; // 12 - 9 = 3 
                                        $sks_diatas_13 = $sksRequest - $sks13; // sisa sks di atas 13 | 5 - 3 = 2  
                                        $nilaiSks =  ($sksUnder10 * 1) + ($sksBetween * 0.5) + ($sks_diatas_13 * 1);  
                                     
                                    }else{
                                        // hitung jumlah sks 
                                        $sks13= 12 - $sksNow; // Sks yang dibutuhkan untuk mencapai 13
                                        $sks_diatas_13 = $sksRequest - $sks13; // sisa sks di atas 13
                                        $nilaiSks = ($sks13 * 0.5) + ($sks_diatas_13 * 1);
                                    }

                                    // // hitung jumlah sks 
                                    // $sks13= 12 - $sksNow; // Sks yang dibutuhkan untuk mencapai 13
                                    // $sks_diatas_13 = $sksRequest - $sks13; // sisa sks di atas 13
                                    // $nilaiSks = ($sks13 * 0.5) + ($sks_diatas_13 * 1);  
                                    // $validatedData['kode'] = 'II.A.1.b';
                               

                                // Mengambil nilai 'sks_reset' untuk user saat ini
                                pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->pluck('sks_reset')->first();

                                // Memperbarui semua nilai 'sks_reset' untuk user saat ini menjadi 0
                                pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->update(['sks_reset' => 0]);

                                $validatedData['sks_reset'] = $sks_diatas_13;
                                $validatedData['kode'] = 'II.A.2.a';
                            }

                            // Set nilai angka kredit sesuai dengan nilai SKS
                            $validatedData['angka_kredit'] = $nilaiSks;
                            $validatedData['sks'] = $sksRequest;
                            $validatedData['komponen_kegiatan'] = 'Mengajar';


                    }

                }

             
            
                // input dokter_klinis
                if ($request->filled('dokter_klinis') && $request->input('radio_option_1') == 'b') {
                    // Kode yang akan dieksekusi jika 'dokter_klinis' ada dalam permintaan, ada dalam permintaan dan tidak kosong.
                    // isi dokter klinis itu kode ex. II.A.3.a
                   // Daftar kode dan nilai Angka Kredit yang sesuai
                        $kode_angka_kredit = [
                            'II.A.3.a' => 4,
                            'II.A.3.b' => 2,
                            'II.A.3.c' => 2,
                            'II.A.3.d' => 3,
                            'II.A.3.e' => 1,
                        ];

                        // Mengambil nilai Angka Kredit saat ini
                        $akNow = $request->dokter_klinis_now;

                        // Mengambil kode yang dipilih oleh pengguna
                        $selected_kode = $request->input('dokter_klinis');

                        // Memeriksa apakah kode yang dipilih ditemukan dalam daftar kode_angka_kredit
                        if (array_key_exists($selected_kode, $kode_angka_kredit)) {
                            // Jika ditemukan, mengambil nilai Angka Kredit yang sesuai dengan kode
                            $angka_kredit = $kode_angka_kredit[$selected_kode];

                            //     $rumus1 = $akNow + $angka_kredit ; // 10 + 3 = 13
                            //     $rumus2 = 11 - $rumus1; // 11 - 13 = 2
                            //     $total_angka_kredit = $rumus1 - $rumus2; // 13 - 2 = 11

                                // // cek nilai angka kredit sekarang jika 11 maka error
                                //     if ($akNow == 11) {
                                //         Alert::error('Total Angka Kredit melebihi batas maksimum.');
                                //         return redirect()->back()->withInput()->withErrors(['dokter_klinis' => 'Total Angka Kredit melebihi batas maksimum.']);
                                //     } else {
                                //         // tambahkan angka kredit ke nilai sekarang
                                //         $rumus1 = $akNow + $angka_kredit;
                                        
                                //         // cek jika total angka kredit melebihi 11
                                //         if ($rumus1 > 11) {
                                //             $rumus2 = $rumus1 - 11;
                                //             $total_angka_kredit = $rumus1 - $rumus2;

                                //             $validatedData['angka_kredit'] = $rumus2;
                                //             $validatedData['kode'] = $request->input('dokter_klinis');
                                //         } else {
                                //             $total_angka_kredit = $rumus1;

                                //             $validatedData['angka_kredit'] = $angka_kredit;
                                //             $validatedData['kode'] = $request->input('dokter_klinis');
                                //         }
                                //         # Gunakan $total_angka_kredit di bagian lain dari kode jika diperlukan
                                //     }


                                
                                $validatedData['angka_kredit'] =  $angka_kredit;
                                $validatedData['kode'] = $request->input('dokter_klinis');

                                if($request->input('dokter_klinis') == 'II.A.3.a'){
                                    $validatedData['komponen_kegiatan'] = 'Melaksanakan pengajaran untuk peserta pendidikan dokter melalui tindakan medik spesialistik';
                                }elseif($request->input('dokter_klinis') == 'II.A.3.b' ){
                                    $validatedData['komponen_kegiatan'] = 'Melakukan pengajaran konsultasi spesialis kepala peserta pendidikan dokter';
                                }elseif($request->input('dokter_klinis') == 'II.A.3.c' ){
                                    $validatedData['komponen_kegiatan'] = 'Melakukan pemeriksaan luar dengan pembimbingan terhadap peserta pendidikan dokter';
                                }elseif($request->input('dokter_klinis') == 'II.A.3.d' ){
                                    $validatedData['komponen_kegiatan'] = 'Melakukan pemeriksaan dalam dengan pembimbingan terhadap peserta pendidikan dokter';
                                }elseif($request->input('dokter_klinis') == 'II.A.3.e' ){
                                    $validatedData['komponen_kegiatan'] = 'Menjadi saksi ahli dengan pembimbingan terhadap peserta pendidikan dokter';
                                }

                           
                        } else {
                            // Jika kode tidak ditemukan dalam daftar, mengatur nilai Angka Kredit menjadi default (misalnya 0)
                            $validatedData['angka_kredit'] = 0;
                        }

                } 
                elseif($request->input('radio_option_1') == 'b') {
                    // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
                    // validasi
                    $validator = Validator::make($request->all(), [
                        'dokter_klinis' => 'max:255|required',
                    ],[
                        'dokter_klinis.required' => 'Kegiatan Dokter Klinis Harus diisi',
                        'dokter_klinis.max' => 'dokter klinis tidak boleh lebih dari 255 karakter'
                    ]);

                    // error
                    if ($validator->fails()) {
                        Alert::error($validator->errors()->first());
                        return redirect()->back()->withInput()->withErrors($validator);
                    }
                }

            // =========================== 4
            // Membimbing seminar mahasiswa (setiap mahasiswa)
                if($request->input('tipe_kegiatan') == 'Membimbing Seminar Mahasiswa (Setiap Mahasiswa)'){
                    $validatedData['angka_kredit'] = 1;
                    $validatedData['kode'] = 'II.B';
                }

            // =========================== 5
            // Membimbing KKN, Praktik Kerja Nyata, Praktik Kerja Lapangan
                if($request->input('tipe_kegiatan') == 'Membimbing KKN, Praktik Kerja Nyata, Praktik Kerja Lapangan'){
                    $validatedData['angka_kredit'] = 1;
                    $validatedData['kode'] = 'II.C';
                }
            


            // error kalo ada dua input bersamaan pada Pembimbing 1 dan Pembimbing 2
            if($request->input('name_pembimbing1') != NULL && $request->input('name_pembimbing2') != NULL ){
                Alert::error('Anda memilih pembimbing 1 dan pembimbing 2 secara bersamaan');
                return redirect()->back()->withErrors(['buat_error' => 'Anda memilih pembimbing 1 dan pembimbing 2 secara bersamaan'])->withInput();

            }

            // =============================== 6
            // PA 1 Membimbing Disertasi, Tesis, Skripsi, dan Laporan Hasil Studi
            if ($request->filled('name_pembimbing1') && $request->input('radio_option_2') == 'a') {
                // kode
                    $p1_disertasi = 'II.D.1.a';
                    $p1_tesis = 'II.D.1.b';
                    $p1_skripsi = 'II.D.1.c';
                    $p1_laporan_akhir_studi = 'II.D.1.d';
           
                $kode = $request->input('name_pembimbing1');

                // cek kode Disertasi
                    if($kode == $p1_disertasi){

                        $validatedData['angka_kredit'] = 8;
                        $validatedData['kode'] = $kode;
                        $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Disertasi';

                        // $max_lulusan = 4;
                        // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                // ->where('kode','II.D.1.a')->count();
                        // cek maksimal lulusan
                            // if($count_lulusan >= $max_lulusan){
                            //     Alert::error('Jumlah lulusan sebagai Pembimbing Utama Disertasi melebihi batas maksimum');
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Disertasi melebihi batas maksimum']);
                            // }else{
                            //     $validatedData['angka_kredit'] = 8;
                            //     $validatedData['kode'] = $kode;
                            //     $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Disertasi';
                            // }

                // cek kode Tesis
                    }elseif($kode == $p1_tesis){

                        $validatedData['angka_kredit'] = 3;
                        $validatedData['kode'] = $kode;
                        $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Tesis';


                        // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                        //                                                         ->where('kode','II.D.1.b')->count();

                        //     // cek maksimal lulusan
                        //     if($count_lulusan >= 6){
                        //         Alert::error('Jumlah lulusan sebagai Pembimbing Utama Tesis melebihi batas maksimum');
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Tesis melebihi batas maksimum']);
                        //     }else{
                        //         $validatedData['angka_kredit'] = 3;
                        //         $validatedData['kode'] = $kode;
                        //         $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Tesis';
                        //     }

                // cek kode Skripsi
                    }elseif($kode == $p1_skripsi){

                        $validatedData['angka_kredit'] = 1;
                        $validatedData['kode'] = $kode;
                        $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Skripsi';


                        // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                        //                                                         ->where('kode','II.D.1.c')->count();

                        //     // cek maksimal lulusan
                        //     if($count_lulusan >= 8){
                        //         Alert::error('Jumlah lulusan sebagai Pembimbing Utama Skripsi melebihi batas maksimum');
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Skripsi melebihi batas maksimum']);
                        //     }else{
                        //         $validatedData['angka_kredit'] = 1;
                        //         $validatedData['kode'] = $kode;
                        //         $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Skripsi';
                        //     }

                // cek kode Laporan Akhir Studi
                    }elseif($kode == $p1_laporan_akhir_studi){
                        
                        $validatedData['angka_kredit'] = 1;
                        $validatedData['kode'] = $kode;
                        $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Laporan Akhir Studi';

                        
                        // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                        //                                                         ->where('kode','II.D.1.d')->count();

                        //     // cek maksimal lulusan
                        //     if($count_lulusan >= 10){
                        //         Alert::error('Jumlah lulusan sebagai Pembimbing Utama Laporan Akhir Studi melebihi batas maksimum');
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Laporan Akhir Studi melebihi batas maksimum']);
                        //     }else{
                        //         $validatedData['angka_kredit'] = 1;
                        //         $validatedData['kode'] = $kode;
                        //         $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Laporan Akhir Studi';
                        //     }
                    } # end elseif cek kode

            } elseif($request->input('radio_option_2') == 'a') {
                    // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
                    // validasi
                    $validator = Validator::make($request->all(), [
                        'name_pembimbing1' => 'max:255|required',
                    ],[
                        'name_pembimbing1.required' => 'Pembimbing Utama harus diisi',
                        'name_pembimbing1.max' => 'Pembimbing Utama tidak boleh lebih dari 255 karakter'
                    ]);

                    // error
                    if ($validator->fails()) {
                        Alert::error($validator->errors()->first());
                        return redirect()->back()->withInput();
                    }
                }

            
            // PA 2 Membimbing Disertasi, Tesis, Skripsi, dan Laporan Hasil Studi
            if ($request->filled('name_pembimbing2') && $request->input('radio_option_2') == 'b') {
                // kode
                    $p2_disertasi = 'II.D.2.a';
                    $p2_tesis = 'II.D.2.b';
                    $p2_skripsi = 'II.D.2.c';
                    $p2_laporan_akhir_studi = 'II.D.2.d';

                    $kode = $request->input('name_pembimbing2');

                    // cek kode Disertasi
                        if($kode == $p2_disertasi){

                            $validatedData['angka_kredit'] = 6;
                            $validatedData['kode'] = $kode;
                            $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Disertasi';

                            // $max_lulusan = 4;
                            // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                            //                                                         ->where('kode','II.D.2.a')->count();
                            // // cek maksimal lulusan
                            //     if($count_lulusan >= $max_lulusan){
                            //         Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Disertasi melebihi batas maksimum');
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Disertasi melebihi batas maksimum']);
                            //     }else{
                            //         $validatedData['angka_kredit'] = 6;
                            //         $validatedData['kode'] = $kode;
                            //         $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Disertasi';

                            //     }



                    // cek kode Tesis
                        }elseif($kode == $p2_tesis){

                            $validatedData['angka_kredit'] = 2;
                            $validatedData['kode'] = $kode;
                            $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Tesis';


                            // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                            //                                                         ->where('kode','II.D.2.b')->count();
                            // // cek maksimal lulusan
                            //     if($count_lulusan >= 6){
                            //         Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Tesis melebihi batas maksimum');
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Tesis melebihi batas maksimum']);
                            //     }else{
                            //         $validatedData['angka_kredit'] = 2;
                            //         $validatedData['kode'] = $kode;
                            //         $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Tesis';
                            //     }

                        }
                    // cek kode skripsi
                        elseif($kode == $p2_skripsi){

                            $validatedData['angka_kredit'] = 0.5;
                            $validatedData['kode'] = $kode;
                            $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Skripsi';

                            // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                            //                                                         ->where('kode','II.D.2.c')->count();
                            // // cek maksimal lulusan
                            //     if($count_lulusan >= 8){
                            //         Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Skripsi melebihi batas maksimum');
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Skripsi melebihi batas maksimum']);
                            //     }else{
                            //         $validatedData['angka_kredit'] = 0.5;
                            //         $validatedData['kode'] = $kode;
                            //         $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Skripsi';
                            //     }

                        }
                    // cek kode Laporan Akhir Studi
                        elseif($kode == $p2_laporan_akhir_studi){

                            $validatedData['angka_kredit'] = 0.5;
                            $validatedData['kode'] = $kode;
                            $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Laporan Akhir Studi';

                            // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                            //                                                         ->where('kode','II.D.2.d')->count();
                            // // cek maksimal lulusan
                            //     if($count_lulusan >= 10){
                            //         Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Laporan Akhir Studi melebihi batas maksimum');
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Laporan Akhir Studi melebihi batas maksimum']);
                            //     }else{
                            //         $validatedData['angka_kredit'] = 0.5;
                            //         $validatedData['kode'] = $kode;
                            //         $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Laporan Akhir Studi';
                            //     }

                        } # End Else if kode

            } elseif($request->input('radio_option_2') == 'b') {
                // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
                // validasi
                $validator = Validator::make($request->all(), [
                    'name_pembimbing2' => 'max:255|required',
                ],[
                    'name_pembimbing2.required' => 'Pembimbing Pendamping harus diisi',
                    'name_pembimbing2.max' => 'Pembimbing Pendamping tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput();
                }
            }


            // ======================== 7
            // Bertugas Sebagai Penguji Pada Ujian Akhir/Profesi 
                if ($request->filled('penguji_uiian_akhir') && $request->input('tipe_kegiatan') == 'Bertugas Sebagai Penguji Pada Ujian Akhir/Profesi') {
                // deklarasi variabel
                    $ketua_penguji = 'II.E.1';
                    $anggota_penguji = 'II.E.2';
                
                    // ambil request
                    $kode = $request->input('penguji_uiian_akhir');

                    // cek kondisi request yang masuk
                        if($kode == $ketua_penguji){

                            $validatedData['angka_kredit'] = 1;
                            $validatedData['kode'] = $kode;
                            $validatedData['komponen_kegiatan'] = 'Ketua Penguji';


                                // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                //                                                         ->where('kode','II.E.1')->count();
                                // // cek maksimal lulusan
                                //     if($count_lulusan >= 4){
                                //         Alert::error('Jumlah lulusan sebagai Ketua Penguji sudah melebihi batas maksimum');
                                //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Ketua Penguji sudah melebihi batas maksimum']);
                                //     }else{
                                //         $validatedData['angka_kredit'] = 1;
                                //         $validatedData['kode'] = $kode;
                                //         $validatedData['komponen_kegiatan'] = 'Ketua Penguji';
                                //     }

                        }elseif($kode == $anggota_penguji){


                            $validatedData['angka_kredit'] = 0.5;
                            $validatedData['kode'] = $kode;
                            $validatedData['komponen_kegiatan'] = 'Anggota Penguji';
                     
                                // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                //                                                         ->where('kode','II.E.2')->count();
                                // // cek maksimal lulusan
                                //     if($count_lulusan >= 8){
                                //         Alert::error('Jumlah lulusan sebagai Anggota Penguji sudah melebihi batas maksimum');
                                //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Anggota Penguji sudah melebihi batas maksimum']);
                                //     }else{
                                //         $validatedData['angka_kredit'] = 0.5;
                                //         $validatedData['kode'] = $kode;
                                //         $validatedData['komponen_kegiatan'] = 'Anggota Penguji';
                                //     }

                        }

                    

                }elseif($request->input('tipe_kegiatan') == 'Bertugas Sebagai Penguji Pada Ujian Akhir/Profesi') {
                    // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
                    // validasi
                    $validator = Validator::make($request->all(), [
                        'penguji_uiian_akhir' => 'max:255|required',
                    ],[
                        'penguji_uiian_akhir.required' => 'Peran penguji harus diisi jika memilih kegiatan bertugas sebagai penguji pada ujian',
                        'penguji_uiian_akhir.max' => 'Peran penguji tidak boleh lebih dari 255 karakter'
                    ]);

                    // error
                    if ($validator->fails()) {
                        Alert::error($validator->errors()->first());
                        return redirect()->back()->withInput()->withErrors($validator);
                    }
                }

            // ========================== 8
            // Membina Kegiatan Mahasiswa Di Bidang Akademik Dan Kemahasiswaan
                if($request->input('tipe_kegiatan') == 'Membina Kegiatan Mahasiswa Di Bidang Akademik Dan Kemahasiswaan'){
                    
                    $validatedData['angka_kredit'] = 2;
                    $validatedData['kode'] = 'II.F';

                    // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                    //                                                         ->where('kode','II.F')->count();
                    // // cek maksimal kegiatan
                    //     if($count_lulusan >= 2){
                    //         Alert::error('Kegiatan Membina kegiatan mahasiswa di bidang akademik dan kemahasiswan sudah maksimum');
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Kegiatan Membina kegiatan mahasiswa di bidang akademik dan kemahasiswan sudah maksimum']);
                    //     }else{
                    //         $validatedData['angka_kredit'] = 2;
                    //         $validatedData['kode'] = 'II.F';
                    //     }    

                }

        
        // ========================== 9
            // Mengembangkan Program Kuliah
            if($request->input('tipe_kegiatan') == 'Mengembangkan Program Kuliah'){
                
                $validatedData['angka_kredit'] = 2;
                $validatedData['kode'] = 'II.G';
                $validatedData['komponen_kegiatan'] = 'Mengembangkan program kuliah yang mempunyai nilai kebaharuan metode atau subtansi (setiap produk)';

                // $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                //                                                         ->where('kode','II.G')->count();
                // // cek maksimal mata kuliah/semester
                //     if($count_lulusan >= 1){
                //         Alert::error('Menggembangkan program kuliah sudah melebihi batas maksimum');
                //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Menggembangkan program kuliah sudah melebihi batas maksimum']);
                //     }else{
                //         $validatedData['angka_kredit'] = 2;
                //         $validatedData['kode'] = 'II.G';
                //         $validatedData['komponen_kegiatan'] = 'Mengembangkan program kuliah yang mempunyai nilai kebaharuan metode atau subtansi (setiap produk)';
                //     }  

            }


        // ============================== 10
        // Mengembangkan Bahan Pengajaran
        if ($request->filled('mengembangkan_bahan_pengajaran') && $request->input('tipe_kegiatan') == 'Mengembangkan Bahan Pengajaran') {
            // deklarasi variabel
                $buku = 'II.H.1';
                $diklat = 'II.H.2';
            
                // ambil request
                $kode = $request->input('mengembangkan_bahan_pengajaran');

                // cek kondisi kalau buku
                   
                            if($kode == $buku){

                                $validatedData['angka_kredit'] = 20;
                                $validatedData['kode'] = $kode;
                                $validatedData['komponen_kegiatan'] = 'Buku Ajar';

                                    // $count_produk = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                    //                                                         ->where('kode', $buku)->count();
                                    // // cek maksimal lulusan
                                    //     if($count_produk >= 1){
                                    //         Alert::error('Jumlah Buku Ajar maksimal 1');
                                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah Buku Ajar maksimal 1']);
                                    //     }else{
                                    //         $validatedData['angka_kredit'] = 20;
                                    //         $validatedData['kode'] = $kode;
                                    //         $validatedData['komponen_kegiatan'] = 'Buku Ajar';
                                    //     }


                            } // cek kondisi kalau diklat dll
                            elseif($kode == $diklat){


                                $validatedData['angka_kredit'] = 5;
                                $validatedData['kode'] = $kode;
                                $validatedData['komponen_kegiatan'] = substr('Diklat,Modul,Ptunjuk praktikum,Model,Alat bantu, Alat visual, 
                                                                            Naskah	tutorial, Job sheet praktikum terkait dengan 
                                                                            mata kuliah yang dilampau', 0, 500);

                                // $count_produk = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                //                                     ->where('kode', $diklat)->count();
                                // // cek maksimal lulusan
                                //     if($count_produk >= 1){
                                //         Alert::error('Jumlah Diklat, Modul dsb maksimal 1');
                                //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah Diklat, Modul dsb maksimal 1']);
                                //     }else{
                                //         $validatedData['angka_kredit'] = 5;
                                //         $validatedData['kode'] = $kode;
                                //         $validatedData['komponen_kegiatan'] = substr('Diklat,Modul,Ptunjuk praktikum,Model,Alat bantu, Alat visual, 
                                //                                                 Naskah	tutorial, Job sheet praktikum terkait dengan 
                                //                                                 mata kuliah yang dilampau', 0, 500);
                                //     }

                            }

        }elseif($request->input('tipe_kegiatan') == 'Mengembangkan Bahan Pengajaran'){
            // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
                    // validasi
                    $validator = Validator::make($request->all(), [
                        'mengembangkan_bahan_pengajaran' => 'max:255|required',
                    ],[
                        'mengembangkan_bahan_pengajaran.required' => 'Produk yang dihasilkan harus dipilih jika tipe kegiatan mengembangkan bahan pengajaran',
                        'mengembangkan_bahan_pengajaran.max' => 'Peran penguji tidak boleh lebih dari 255 karakter'
                    ]);

                    // error
                    if ($validator->fails()) {
                        Alert::error($validator->errors()->first());
                        $errors = $validator->errors()->all();
                        return redirect()->back()->withInput()->withErrors(['buat_error' => implode(' ', $errors)]);
                    }
        }


        // ========================== 11
        // Menyampaikan Orasi Ilmiah
        if($request->input('tipe_kegiatan') == 'Menyampaikan Orasi Ilmiah'){
            
             // deklarasi kode orasi
                $orasi = 'II.I';

                $validatedData['angka_kredit'] = 5;
                $validatedData['kode'] = $orasi;

                // $count_orasi = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                //                                                     ->where('kode',$orasi)->count();
            
                    // // cek maksimal orasi
                    //     if($count_orasi >= 2){
                    //         Alert::error('Maksimal 2 orasi');
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => 'Maksimal 2 orasi']);
                    //     }else{
                    //         $validatedData['angka_kredit'] = 5;
                    //         $validatedData['kode'] = $orasi;
                    //     }               
        }

        // =========================== 12
        // Menduduki Jabatan Pimpinan Perguruan Tinggi
        if ($request->filled('menduduki_jabatan_pimpinan') && $request->input('tipe_kegiatan') == 'Menduduki Jabatan Pimpinan Perguruan Tinggi') {
            // deklarasi variabel
                $kode_1 = 'II.J.1'; // rektor
                $kode_2 = 'II.J.2'; // wakil_rektor
                $kode_3 = 'II.J.3'; //ketua_sekolah_tinggi
                $kode_4 = 'II.J.4'; // wakil_ketua_sekolah_tinggi
                $kode_5 = 'II.J.5'; // direktur_akademi
                $kode_6 = 'II.J.6'; // wakil_direktur_pada_univ
                $kode_7 = 'II.J.7'; // wakil_direktur_pada_politeknik_sekreataris_bagian_pada_univ
                $kode_8 = 'II.J.8'; // sek_jurusan
        
            // ambil request
                $kode_request = $request->input('menduduki_jabatan_pimpinan');

            // cek kondisi kalau rektor kode 1 dan wakil rektro kode 2
                if($kode_request == $kode_1 || $kode_request == $kode_2){

                    // ambil nilai dari rektor maupun wakil rektor
                        // $count_rektor = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                        //                                                                     ->where('kode',$kode_1)->count();
                        // $count_wakil_rektor = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                        //                                                                 ->where('kode',$kode_2)->count();

                    
                        // cek maksimal jabatan
                            // if($count_rektor >= 1 || $count_wakil_rektor >= 1){
                            //     Alert::error('Hanya dapat memiliki maksimal 1 jabatan sebagai rektor ataupun wakil rektor.');
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => 'Hanya dapat memiliki maksimal 1 jabatan sebagai rektor ataupun wakil rektor.']);
                            // }
                        // kalau belum maksimal tambahkan angka kredit sesuai request


                        if($kode_request == $kode_1 ){
                            $validatedData['angka_kredit'] = 6;
                            $validatedData['kode'] = $kode_1;
                            $validatedData['komponen_kegiatan'] = 'Rektor';
                        }elseif($kode_request == $kode_2){
                            $validatedData['angka_kredit'] = 5;
                            $validatedData['kode'] = $kode_2;
                            $validatedData['komponen_kegiatan'] = 'Wakil rektor/ dekan/ direktur/ program pasca sarjana/ketua lembaga';
                        }

                }  # End Rektor and wakil

                // cek kondisi kalau ketua sekolah tinggi kode 3
                elseif($kode_request == $kode_3){
                    $validatedData['angka_kredit'] = 4;
                    $validatedData['kode'] = $kode_3;
                    $validatedData['komponen_kegiatan'] = 'Ketua Sekolah Tinggi/Pembantu Dekan/ Asisten Direktur Program Pascasarjana/
                                                            Direktur Politeknik/Koordinator Kopertis';
                }
                // cek kondisi kalau wakil sekolah tinggi kode 4
                elseif($kode_request == $kode_4){
                    $validatedData['angka_kredit'] = 4;
                    $validatedData['kode'] = $kode_4;
                    $validatedData['komponen_kegiatan'] = 'Pembantu Ketua Sekolah Tinggi/Pembantu Direktur Politeknik';
                }
                // cek kondisi kalau direktur_akademi kode 5
                elseif($kode_request == $kode_5){
                    $validatedData['angka_kredit'] = 4;
                    $validatedData['kode'] = $kode_5;
                    $validatedData['komponen_kegiatan'] = 'Direktur Akademi';
                }
                // cek kondisi kalau wakil_direktur_pada_univ kode 6
                elseif($kode_request == $kode_6){
                    $validatedData['angka_kredit'] = 3;
                    $validatedData['kode'] = $kode_6;
                    $validatedData['komponen_kegiatan'] = 'Pembantu direktur politeknik, ketua jurusan/bagian pada universitas/institut/sekolah	tinggi';
                }
                // cek kondisi kalau wakil_direktur_pada_politeknik_sekreataris_bagian_pada_univ kode 7
                elseif($kode_request == $kode_7){
                    $validatedData['angka_kredit'] = 3;
                    $validatedData['kode'] = $kode_7;
                    $validatedData['komponen_kegiatan'] = 'Pembantu direktur politelnik/ketua jurusan/ketua prodi pada universitas/politeknik/akademi, 
                                                            sekretaris jurusan/bagian pada universitas/institut/sekolah tinggi';
                }
                // cek kondisi kalau  sek_jurusan kode 8
                elseif($kode_request == $kode_8){
                    $validatedData['angka_kredit'] = 3;
                    $validatedData['kode'] = $kode_8;
                    $validatedData['komponen_kegiatan'] = 'Sekretaris jurusan pada politeknik/akademi dan kepala laboratorium (bengkel) 
                                                            universitas/ institut/sekolah tinggi/politeknik/akademi';
                }
                // kalau request tidak sesuai 404 
                else{
                    abort(404);
                }
               

            
        }elseif($request->input('tipe_kegiatan') == 'Menduduki Jabatan Pimpinan Perguruan Tinggi') {
            // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
            // validasi
            $validator = Validator::make($request->all(), [
                'menduduki_jabatan_pimpinan' => 'max:255|required',
            ],[
                'menduduki_jabatan_pimpinan.required' => 'Jabatan harus diisi jika memilih Menduduki jabatan',
                'menduduki_jabatan_pimpinan.max' => 'Menduduku jabatan tidak boleh lebih dari 255 karakter'
            ]);

            // error
            if ($validator->fails()) {
                Alert::error($validator->errors()->first());
                $errors = $validator->errors()->all();
                return redirect()->back()->withInput()->withErrors(['buat_error' => implode(' ', $errors)]);
            }
        }


        // =============================== 13
        // Membimbing Dosen Yang Mempunyai Jabatan Akademik Lebih Rendah 
        if ($request->filled('bimbing_dosen_rendah') && $request->input('tipe_kegiatan') == 'Membimbing Dosen Yang Mempunyai Jabatan Akademik Lebih Rendah') {

            // deklarasi variabel untuk mengambil pangkat user 
                $bukan_lektor_kepala = User::where('id', auth()->user()->id)->whereIn('pangkat_id', [1, 2,3,4])->first();
                $lektor_kepala = User::where('id', auth()->user()->id)->whereIn('pangkat_id', [5,6,7,8,9])->first();

                // dd([$bukan_lektor_kepala, $lektor_kepala]); 
            // deklarasi kode
                $pembimbing_pencangkokan = 'II.K.1';
                $reguler = 'II.K.2';

            // ambil kode
                $kode_request = $request->input('bimbing_dosen_rendah');
            
                // cek pangkat user kalau bukan lektor kepala error
                if($bukan_lektor_kepala){
                    $errorMessage = 'Hanya Pangkat Lektor Kepala yang dapat menambahkan bimbingan dosen dengan pangkat yang lebih rendah.';
                    Alert::error($errorMessage);
                    return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                }elseif($lektor_kepala){

                    // jika lektor kepala check kode pembimbing_pencangkokan dan reguler
                        // pembimbing pencangkokan
                        if($kode_request == $pembimbing_pencangkokan){
                            

                            $validatedData['angka_kredit'] = 2;
                            $validatedData['kode'] = $pembimbing_pencangkokan;
                            $validatedData['komponen_kegiatan'] = 'Pembimbing Pencangkokan';

                                // ambil count maksimal
                                // $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                                //                                                         ->where('kategori_pak_id',1)
                                //                                                         ->where('kode', $pembimbing_pencangkokan)->count();
                        
                                // // cek maksimal 
                                //     if($count_maksimal >= 1){
                                //         $errorMessage = 'Pembimbing Pencangkokan sudah maksimal';
                                //         Alert::error($errorMessage);
                                //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                //     }else{
                                //         $validatedData['angka_kredit'] = 2;
                                //         $validatedData['kode'] = $pembimbing_pencangkokan;
                                //         $validatedData['komponen_kegiatan'] = 'Pembimbing Pencangkokan';
                                //     }

                        }
                        // pembimbing Reguler
                        elseif($kode_request == $reguler){

                            $validatedData['angka_kredit'] = 1;
                            $validatedData['kode'] = $reguler;
                            $validatedData['komponen_kegiatan'] = 'Pembimbing Reguler';

                                // ambil count maksimal
                                // $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                                //                                                         ->where('kategori_pak_id',1)
                                //                                                         ->where('kode', $reguler)->count();
                        
                                // // cek maksimal 
                                // if($count_maksimal >= 1){
                                //     $errorMessage = 'Pembimbing Reguler sudah maksimal';
                                //     Alert::error($errorMessage);
                                //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                // }else{
                                //     $validatedData['angka_kredit'] = 1;
                                //     $validatedData['kode'] = $reguler;
                                //     $validatedData['komponen_kegiatan'] = 'Pembimbing Reguler';
                                // }
                        
                        }


                }else{
                    // jika pangkat user tidak sesuai dengan deklarasi sebelumnya 1-9
                    abort(404);
                }

                
        }elseif($request->input('tipe_kegiatan') == 'Membimbing Dosen Yang Mempunyai Jabatan Akademik Lebih Rendah') {
            // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
            // validasi
            $validator = Validator::make($request->all(), [
                'bimbing_dosen_rendah' => 'max:255|required',
            ],[
                'bimbing_dosen_rendah.required' => 'Jika tipe kegiatan membimbing dosen yang mempunyai jabatan akademik lebih rendah harus memilih antara pembimbing pencangkokan atau reguler',
                'bimbing_dosen_rendah.max' => 'boleh lebih dari 255 karakter'
            ]);

            // error
            if ($validator->fails()) {
                Alert::error($validator->errors()->first());
                $errors = $validator->errors()->all();
                return redirect()->back()->withInput()->withErrors(['buat_error' => implode(' ', $errors)]);
            }
        }


        // =============================== 14
        // Melaksanakan Kegiatan Detasering Dan Pencangkokan Di Luar Institusi
        if ($request->filled('kegiatan_detasering_dan_pencangkokan') && $request->input('tipe_kegiatan') == 'Melaksanakan Kegiatan Detasering Dan Pencangkokan Di Luar Institusi') {

            // deklarasi variabel untuk mengambil pangkat user 
                $bukan_lektor_kepala = User::where('id', auth()->user()->id)->whereIn('pangkat_id', [1, 2,3,4])->first();
                $lektor_kepala = User::where('id', auth()->user()->id)->whereIn('pangkat_id', [5,6,7,8,9])->first();

            // deklarasi kode
                $detaseting = 'II.L.1';
                $pencangkokan = 'II.L.2';

            // ambil kode
                $kode_request = $request->input('kegiatan_detasering_dan_pencangkokan');
            
                // cek pangkat user kalau bukan lektor kepala error
                if($bukan_lektor_kepala){
                    $errorMessage = 'Hanya Pangkat Lektor Kepala yang dapat menambahkan kegiatan detasering dan pencangkokan di luar institusi';
                    Alert::error($errorMessage);
                    return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                }elseif($lektor_kepala){

                    // jika lektor kepala check kode pembimbing_pencangkokan dan reguler
                        // Detasering
                        if($kode_request == $detaseting){

                            $validatedData['angka_kredit'] = 5;
                            $validatedData['kode'] = $kode_request;
                            $validatedData['komponen_kegiatan'] = 'Detasering';

                            // ambil count maksimal
                            //     $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                            //                                                             ->where('kategori_pak_id',1)
                            //                                                             ->where('kode', $detaseting)->count();
                        
                            // // cek maksimal 
                            //     if($count_maksimal >= 1){
                            //         $errorMessage = 'Kegiatan Detasering di luar institusi sudah maksimal';
                            //         Alert::error($errorMessage);
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            //     }else{
                            //         $validatedData['angka_kredit'] = 5;
                            //         $validatedData['kode'] = $kode_request;
                            //         $validatedData['komponen_kegiatan'] = 'Detasering';
                            //     }

                        }
                        // Pencangkokan
                        elseif($kode_request == $pencangkokan){

                            $validatedData['angka_kredit'] = 4;
                            $validatedData['kode'] = $kode_request;
                            $validatedData['komponen_kegiatan'] = 'Pencangkokan';


                                // ambil count maksimal
                                // $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                                //                                                         ->where('kategori_pak_id',1)
                                //                                                         ->where('kode', $pencangkokan)->count();
                        
                                // // cek maksimal 
                                // if($count_maksimal >= 1){
                                //     $errorMessage = 'Kegiatan Pencangkokan di luar institusi sudah maksimal';
                                //     Alert::error($errorMessage);
                                //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                // }else{
                                //     $validatedData['angka_kredit'] = 4;
                                //     $validatedData['kode'] = $kode_request;
                                //     $validatedData['komponen_kegiatan'] = 'Pencangkokan';
                                // }
                        
                        }


                }else{
                    // jika pangkat user tidak sesuai dengan deklarasi sebelumnya 1-9
                    abort(404);
                }

                
        }elseif($request->input('tipe_kegiatan') == 'Melaksanakan Kegiatan Detasering Dan Pencangkokan Di Luar Institusi') {
            // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
            // validasi
            $validator = Validator::make($request->all(), [
                'kegiatan_detasering_dan_pencangkokan' => 'max:255|required',
            ],[
                'kegiatan_detasering_dan_pencangkokan.required' => 'Jika memilih Kegiatan Detasering Dan Pencangkokan Di Luar Institusi maka harus memilih antara pencangkokan atau detasering',
                'kegiatan_detasering_dan_pencangkokan.max' => 'tidak boleh lebih dari 255 karakter'
            ]);

            // error
            if ($validator->fails()) {
                Alert::error($validator->errors()->first());
                $errors = $validator->errors()->all();
                return redirect()->back()->withInput()->withErrors(['buat_error' => implode(' ', $errors)]);
            }
        }


        // =============================== 15
        // Melaksanakan Pengembangan Diri Untuk Meningkatkan Kompetensi
        if ($request->filled('pengembangan_diri') && $request->input('tipe_kegiatan') == 'Melaksanakan Pengembangan Diri Untuk Meningkatkan Kompetensi') {
            // deklarasi variabel
                $kode_1 = 'II.M.1'; // > 960
                $kode_2 = 'II.M.2'; // 641 960
                $kode_3 = 'II.M.3'; // 481 640
                $kode_4 = 'II.M.4'; // 161 480
                $kode_5 = 'II.M.5'; // 81 160
                $kode_6 = 'II.M.6'; // 30 80
                $kode_7 = 'II.M.7'; // 10 30
 
        
            // ambil request
                $kode_request = $request->input('pengembangan_diri');

            // cek kondisi > 960 jam
                if($kode_request == $kode_1){
                    $validatedData['angka_kredit'] = 15;
                    $validatedData['kode'] = $kode_request;
                    $validatedData['komponen_kegiatan'] = 'Lamanya lebih dari 960 jam';

                }  
                // cek kondisi 641 - 960
                elseif($kode_request == $kode_2){
                    $validatedData['angka_kredit'] = 9;
                    $validatedData['kode'] = $kode_request;
                    $validatedData['komponen_kegiatan'] = 'Lamanya antara 641-960 jam';
                }
                // cek kondisi 481 - 640
                elseif($kode_request == $kode_3){
                    $validatedData['angka_kredit'] = 6;
                    $validatedData['kode'] = $kode_request;
                    $validatedData['komponen_kegiatan'] = 'Lamanya antara 481-640 jam';
                }
                // cek kondisi 161 - 480
                elseif($kode_request == $kode_4){
                    $validatedData['angka_kredit'] = 3;
                    $validatedData['kode'] = $kode_4;
                    $validatedData['komponen_kegiatan'] = 'Lamanya antara 161-960 jam';
                }
                // cek kondisi 81 160
                elseif($kode_request == $kode_5){
                    $validatedData['angka_kredit'] = 2;
                    $validatedData['kode'] = $kode_5;
                    $validatedData['komponen_kegiatan'] = 'Lamanya antara 81-160 jam';
                }
                // cek kondisi 30 80
                elseif($kode_request == $kode_6){
                    $validatedData['angka_kredit'] = 1;
                    $validatedData['kode'] = $kode_6;
                    $validatedData['komponen_kegiatan'] = 'Lamanya antara 30-80 jam';
                }
                // cek kondisi kalau wakil_direktur_pada_politeknik_sekreataris_bagian_pada_univ kode 7
                elseif($kode_request == $kode_7){
                    $validatedData['angka_kredit'] = 0.5;
                    $validatedData['kode'] = $kode_7;
                    $validatedData['komponen_kegiatan'] = 'Lamanya antara 10-30 jam';
                }
                // kalau request tidak sesuai 404 
                else{
                    abort(404);
                }
               

            
        }elseif($request->input('tipe_kegiatan') == 'Melaksanakan Pengembangan Diri Untuk Meningkatkan Kompetensi') {
            // Kode yang akan dieksekusi jika salah satu atau kedua kondisi tidak terpenuhi
            // validasi
            $validator = Validator::make($request->all(), [
                'pengembangan_diri' => 'max:255|required',
            ],[
                'pengembangan_diri.required' => 'Jam harus diisi jika memilih Melaksanakan Pengembangan Diri',
                'pengembangan_diri.max' => 'Tdak boleh lebih dari 255 karakter'
            ]);

            // error
            if ($validator->fails()) {
                Alert::error($validator->errors()->first());
                $errors = $validator->errors()->all();
                return redirect()->back()->withInput()->withErrors(['buat_error' => implode(' ', $errors)]);
            }
        }




        // ubah nama slug 
            $slug = Str::slug($request->input('kegiatan'));
            $random_token = Str::random(5);
            $slug_name = $slug.'-'. $random_token;

            $validatedData['slug'] = $slug_name;
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['kategori_pak_id'] = 1;
            
        // Handle upload bukti pdf
        $namadosen = auth()->user()->name;
        $direktori_dosen = Str::slug($namadosen);
        $nama_bukti = Str::slug($request->input('kegiatan'));
    
        $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/'. $direktori_dosen, time().'-'.$nama_bukti.'.pdf');


            // dd($validatedData);
  
  
            // Create 
            pak_kegiatan_pendidikan_dan_pengajaran::create($validatedData);
    
            Alert::success('Berhasil','Menambahkan Kegiatan');
            return redirect()->route('pendidikan-dan-pengajaran');
        
    }

    public function pendidikan_dan_pengajaran_detail($detail){
        
         // Retrieve the record from the table based on the slug
        $record = pak_kegiatan_pendidikan_dan_pengajaran::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        return view('dosen.simulasi.pendidikan_dan_pengajaran.detail',[
            'title' => 'Pendidikan dan Pengajaran',
            'record' => $record,
        ]);

    }

    public function pendidikan_dan_pengajaran_edit_store($detail, Request $request){
        // Retrieve the record from the table based on the slug
            $record = pak_kegiatan_pendidikan_dan_pengajaran::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }


        $validator = Validator::make($request->all(),[
            // 'kegiatan' => 'regex:/^[a-zA-Z0-9\s]+$/|max:255',
            'kegiatan' => '|max:255',
            'bukti' => 'mimes:pdf|max:1024|',
        ],[
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'kegiatan.regex' => 'Nama Kegiatan hanya boleh mengandung huruf dan spasi',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
        ]);

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Mengedit Data');
        }

        // simpan data
        $validatedData = $validator->validated();

        if ($request->input('kegiatan') ==  NULL){
            $validator = Validator::make($request->all(),[
                'kegiatan' => 'required',
            ],[
                'kegiatan.required' => 'Kegiatan harus diisi',
            ]);

            if ($validator->fails()) {
                Alert::error($validator->errors()->all()[0]);
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }


        if (!$request->hasFile('bukti')) {
            // Handle the case where the user didn't provide a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            // Use Storage::copy to copy the existing file to the desired directory
            $newFilePath = 'dosen/' . $direktori_dosen . '/' . time() . '-' . $nama_bukti . '.pdf';
            Storage::copy($record->bukti, $newFilePath);
        
            $validatedData['bukti'] = $newFilePath;
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        } else {
            // Handle the case where the user provided a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/' . $direktori_dosen, time() . '-' . $nama_bukti . '.pdf');
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        }
        
    
        // Create 
            pak_kegiatan_pendidikan_dan_pengajaran::where('slug', $detail)->update($validatedData);

            Alert::success('Berhasil','Update Data');
            return redirect()->back();

    }

    # Penelitian
    ############################################
    public function penelitian(){
        return view('dosen.simulasi.penelitian.index',[
            'title' => 'Simulasi Pendidikan dan Pengajaran',
            'all' => pak_kegiatan_penelitian::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {
                                                                            $query->where('now', true);
                                                                    })
                                                                    ->where('user_id', auth()->user()->id)->where('kategori_pak_id', 2)
                                                                    ->orderBy('id', 'DESC')
                                                                    ->get(),
            'tahun_ajaran' => tahun_ajaran::where('now', true)->get(),
            'total_kredit' => pak_kegiatan_penelitian::QueryTotalAK()->sum('angka_kredit'),
        # 1 a)    
            'k_i__buku_refrensi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.1')->sum('angka_kredit'),
            'k_i__monograf' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.2')->sum('angka_kredit'),
        # 1 b)
            'buku_internasional' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.2.1')->sum('angka_kredit'),
            'buku_nasional' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.2.2')->sum('angka_kredit'),
        # 1 c)
            'jurnal_int_bereputasi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.1.1')->sum('angka_kredit'),
            'jurnal_int_terindek_db_bereputpasi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.1.2')->sum('angka_kredit'),
            'jurnal_int_terindek_db_int_luar_kategori_2' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.1.3')->sum('angka_kredit'),
            'jurnal_nas_terakreditasi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.2')->sum('angka_kredit'),
            'jurnal_nas_bhs_indonesia_doaj' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.2.1')->sum('angka_kredit'),
            'jurnal_nas_bhs_inggris_doaj' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.2.2')->sum('angka_kredit'),
            'jurnal_nasional' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.3')->sum('angka_kredit'),
            'jurnal_bhs_resmi_pbb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.3.1')->sum('angka_kredit'),
        # 2 a
            'dimuat_dalam_prosiding_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.a.1')->sum('angka_kredit'),
            'dimuat_dalam_prosiding_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.2.b')->sum('angka_kredit'),
        # 2 b
            'poster_dalam_prosiding_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.2.a')->sum('angka_kredit'),
            'poster_dalam_prosiding_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.b.1')->sum('angka_kredit'),
        # 2 c
            'int_tanpa_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.a')->sum('angka_kredit'),
            'nas_tanpa_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.b')->sum('angka_kredit'),
        # 2 d
            'int_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.3.a')->sum('angka_kredit'),
            'nas_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.3.b')->sum('angka_kredit'),
        # 2 e
            'disajikan_dalam_koran_majalah_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.d')->sum('angka_kredit'),
        # 3 
            'hasil_penelitian_tidak_dipublikasikan_tersimpan_perpustakaan' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.2')->sum('angka_kredit'),
        # 4
            'menerjemahkan_buku_ilmiah_isbn' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.B')->sum('angka_kredit'),
        # 5 
            'menyunting_karya_ilmiah_bentuk_buku_isbn' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.C')->sum('angka_kredit'),
        # 6 
            'paten_rancangan_teknologi_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.D.1')->sum('angka_kredit'),
            'paten_rancangan_teknologi_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.D.2')->sum('angka_kredit'),
        # 7
            'tanpa_paten_rancangan_teknologi_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.1')->sum('angka_kredit'),
            'tanpa_paten_rancangan_teknologi_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.2')->sum('angka_kredit'),
            'tanpa_paten_rancangan_teknologi_lokal' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.3')->sum('angka_kredit'),
        # 8
            'tanpa_hki_rancangan_teknologi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.4')->sum('angka_kredit'),

        ]);
    }

    public function penelitian_tambah(){

        $tipe_kegiatan = tipe_kegiatan_penelitian::all();
        
        return view('dosen\simulasi\penelitian\tambah',[
            'title' => 'Simulasi Penelitian',
            'tipe_kegiatan' => $tipe_kegiatan,
            't_a' => tahun_ajaran::where('now', 1)->value('tahun'),
            'tahun_ajaran_hidden' =>  tahun_ajaran::where('now',1)->first(),
            'semester' => tahun_ajaran::where('now', 1)->value('semester'),
        ]);

    }

    public function penelitian_tambah_store(Request $request){

        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'kegiatan' => 'required|max:255|',
            'tipe_kegiatan' => 'required|max:255',
            'tahun_ajaran_id' => 'required' ,
            'angka_kredit' => 'required' ,
            'bukti' => 'required|max:1024|mimes:pdf',
        ],[
            'kegiatan.required' => 'Nama Kegiatan Harus diisi',
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'tipe_kegiatan.required' => 'Tipe kegiatan harus diisi',
            'tipe_kegiatan.max' => 'Maksimal 255 karakter',
            'tahun_ajaran.required' => 'Tahun Ajaran harus diisi',
            'angka_kredit.required' => 'Angka Kredit Harus Diisi',
            'angka_kredit.numeric' => 'Angka Kredit Hanya Mengandung Angka',
            'bukti.required' => 'Bukti harus diupload',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
        ]);
    

        if($request->input('tipe_kegiatan') == 'default'){
            $errorMessage = 'Tipe Kegiatan Harus Diisi';
            Alert::error($errorMessage);
            return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        }


        // if($request->input('tipe_kegiatan') != NULL && $request->input('komponen_kegiatan') == NULL){
        // // if($request->input('tipe_kegiatan_id') == 1 ){
        //     $errorMessage = 'Anda Tidak Memilih Komponen Kegiatan';
        //     Alert::error($errorMessage);
        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        // }

        // deklarasi variable yang memiliki komponen
        $have_komponen = [
            'Menghasilkan Karya Ilmiah Sesuai Dengan Bidang Ilmunya', // 1
            'Hasil Penelitian Atau Hasil Pemikiran Yang Didesiminasikan', // 2
            'Membuat Rancangan Dan Karya Teknologi/ Seni Yang Dipatenkan Secara Nasional Atau Internasional', // 6
            'Membuat Rancangan Dan Karya Teknologi Yang Tidak Dipatenkan; Rancangan Dan Karya Seni Monumental/ Seni Pertunjukan; Karya Sastra', // 7
        ];
        
        if (in_array($request->input('tipe_kegiatan'), $have_komponen) &&  $request->input('komponen_kegiatan') == NULL) {
            $errorMessage = 'Anda Tidak Memilih Komponen Kegiatan';
            Alert::error($errorMessage);
            return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        }

        
        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Menambahkan Kegiatan');
        }


        // simpan data
        $validatedData = $validator->validated();


        // 1 Menghasilkan Karya Ilmiah A
            if ($request->filled('dalam_bentuk_buku') && $request->input('komponen_kegiatan') == 'Hasil Penelitian Atau Hasil Dipublikasikan Dalam Bentuk Buku'){
                // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
            
                // declare the variable
                $buku_refrensi = 'II.A.1.a.1';
                $monograf = 'II.A.1.a.2';

                // if refrensi
                if($request->input('dalam_bentuk_buku') == $buku_refrensi){

                    $validatedData['kode'] = $buku_refrensi;
                    $validatedData['nilai_kegiatan'] = 'Buku Refrensi';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');


                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                                 ->where('kode',$buku_refrensi)->sum('angka_kredit');
                    
                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 40){
                    //     $errorMessage = 'Maksimal Angka Kredit Untuk Buku Refrensi Yaitu 40';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }else{
                    //     // periksa lagi so pernah b upload ato blm
                    //         $cek_buku = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$buku_refrensi)->count();
                    //         if($cek_buku == 1){
                    //             $errorMessage = 'Maskimal Buku Refrensi 1 buku/tahun';
                    //             Alert::error($errorMessage);
                    //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //         }

                    //         $validatedData['kode'] = $buku_refrensi;
                    //         $validatedData['nilai_kegiatan'] = 'Buku Refrensi';
                    //         $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    //         $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    // }
                
                //if monograf
                }elseif($request->input('dalam_bentuk_buku') == $monograf){

                    $validatedData['kode'] = $monograf;
                    $validatedData['nilai_kegiatan'] = 'Monograf';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                                 ->where('kode',$monograf)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
         

                    // if($result > 20){
                    //     $errorMessage = 'Maksimal Angka Kredit Untuk Buku Refrensi Yaitu 20';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }else{
                    //     // periksa lagi so pernah b upload ato blm
                    //         $cek_buku = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$monograf)->count();
                    //         if($cek_buku == 1){
                    //             $errorMessage = 'Maskimal Buku Monograf 1 buku/tahun';
                    //             Alert::error($errorMessage);
                    //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //         }

                    //     $validatedData['kode'] = $monograf;
                    //     $validatedData['nilai_kegiatan'] = 'Monograf';
                    //     $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    //     $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    // }

                }

            }elseif($request->input('komponen_kegiatan') == 'Hasil Penelitian Atau Hasil Dipublikasikan Dalam Bentuk Buku'){
                $validator = Validator::make($request->all(), [
                    'dalam_bentuk_buku' => 'max:255|required',
                ],[
                    'dalam_bentuk_buku.required' => 'Hasil Penelitian dalam bentuk buku harus diisi',
                    'dalam_bentuk_buku.max' => 'Hasil Penelitian dalam bentuk buku tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {
                    // Alert::error($validator->errors()->first());
                    // return redirect()->back()->withInput()->withErrors($validator);

                    $errorMessage = 'Nilai dari Kegiatan Hasil Penelitian dalam bentuk buku harus diisi Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }


            }

        // 1 Menghasilkan Karya Ilmiah B
            if ($request->filled('book_chapter') && $request->input('komponen_kegiatan') == 'Hasil Penelitian Atau Hasil Pemikiran Dalam Buku Yang Dipublikasikan Dan Berisi Berbagai Tulisan Dari Berbagai Penulis (Book Chapter)'){

                // declare the variable
                $internasional = 'II.A.1.a.2.1';
                $nasional = 'II.A.1.a.2.2';

                // cek internasional
                if($request->input('book_chapter') == $internasional){

                    $validatedData['kode'] = $internasional;
                    $validatedData['nilai_kegiatan'] = 'Internasional';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        // // validasi maksimal angka kredit 
                        // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                        //                                                 ->where('kode',$internasional)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // if($result > 15){
                        //     $errorMessage = 'Maksimal Angka Kredit Internasional Yaitu 15';
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }else{
                        //     // periksa lagi so pernah b upload ato blm
                        //     $cek_buku = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                        //                     ->where('kode',$internasional)->count();

                        //     if($cek_buku == 1){
                        //         $errorMessage = 'Maksimal 1 buku/tahun';
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        //     $validatedData['kode'] = $internasional;
                        //     $validatedData['nilai_kegiatan'] = 'Internasional';
                        //     $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        //     $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        // }




                // cek Nasional
                }elseif($request->input('book_chapter') == $nasional){

                    $validatedData['kode'] = $nasional;
                    $validatedData['nilai_kegiatan'] = 'Nasional';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        // // validasi maksimal angka kredit 
                        // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                        //                                                 ->where('kode',$nasional)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // if($result > 10){
                        //     $errorMessage = 'Maksimal Angka Kredit Nasional Yaitu 10';
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }else{
                        //     // periksa lagi so pernah b upload ato blm
                        //     $cek_buku = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                        //                                         ->where('kode',$nasional)->count();

                        //     if($cek_buku == 1){
                        //         $errorMessage = 'Maksimal 1 buku/tahun';
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        // $validatedData['kode'] = $nasional;
                        // $validatedData['nilai_kegiatan'] = 'Nasional';
                        // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        // }

                }


            }elseif($request->input('komponen_kegiatan') == 'Hasil Penelitian Atau Hasil Pemikiran Dalam Buku Yang Dipublikasikan Dan Berisi Berbagai Tulisan Dari Berbagai Penulis (Book Chapter)'){
                $validator = Validator::make($request->all(), [
                    'book_chapter' => 'max:255|required',
                ],[
                    'book_chapter.required' => 'Hasil Penelitian harus diisi',
                    'book_chapter.max' => 'Hasil Penelitian tidak boleh lebih dari 255 karakter'
                ]);

                if ($validator->fails()) {
                    $errorMessage = 'Hasil Penelitian Dalam Buku Yang Dipublikasikan Dan Berisi Berbagai Tulisan Dari Berbagai Penulis (Book Chapter) Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }
                

            }


        // 1 Menghasilkan Karya Ilmiah C Jurnal
            if ($request->filled('c_jurnal') && $request->input('komponen_kegiatan') == 'Hasil Penelitian Atau Hasil Pemikiran Yang Dipublikasikan'){
                // deklarasi variabel
                $jurnal_1 = 'II.A.1.b.1.1';
                $jurnal_2 = 'II.A.1.b.1.2';
                $jurnal_3 = 'II.A.1.b.1.3';
                $jurnal_4 = 'II.A.1.b.2';
                $jurnal_5a = 'II.A.1.b.2.1';
                $jurnal_5b = 'II.A.1.b.2.2';
                $jurnal_6 = 'II.A.1.b.3';
                $jurnal_7 = 'II.A.1.b.3.1';

                // jurnal 1
                if($request->input('c_jurnal') == $jurnal_1){

                    $validatedData['kode'] = $jurnal_1;
                    $validatedData['nilai_kegiatan'] = 'Jurnal Internasional Bereputasi (Terindek Pada Database Internasional Bereputasi Dan Berfakrot Dampak)';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        // // validasi maksimal angka kredit 
                        // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                        //                                             ->where('kode',$jurnal_1)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // if($result > 40){
                        //     $errorMessage = 'Maksimal Angka Kredit Yaitu 40';
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        // $validatedData['kode'] = $jurnal_1;
                        // $validatedData['nilai_kegiatan'] = 'Jurnal Internasional Bereputasi (Terindek Pada Database Internasional Bereputasi Dan Berfakrot Dampak)';
                        // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                }
                // jurnal 2    
                elseif($request->input('c_jurnal') ==  $jurnal_2){
                    
                    $validatedData['kode'] = $jurnal_2;
                    $validatedData['nilai_kegiatan'] = 'Jurnal Internasional Terindek Pada Database Internasional Bereputasi';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$jurnal_2)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 30){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 30';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    // $validatedData['kode'] = $jurnal_2;
                    // $validatedData['nilai_kegiatan'] = 'Jurnal Internasional Terindek Pada Database Internasional Bereputasi';
                    // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                }

                // jurnal 3    
                elseif($request->input('c_jurnal') ==  $jurnal_3){
                    
                    $validatedData['kode'] = $jurnal_3;
                    $validatedData['nilai_kegiatan'] = 'Jurnal internasional terindeks pada database internasional di luar kategori 2';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$jurnal_3)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 20){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 20';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    // $validatedData['kode'] = $jurnal_3;
                    // $validatedData['nilai_kegiatan'] = 'Jurnal internasional terindeks pada database internasional di luar kategori 2';
                    // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }

                // jurnal 4 
                elseif($request->input('c_jurnal') ==  $jurnal_4){
                    
                    $validatedData['kode'] = $jurnal_4;
                    $validatedData['nilai_kegiatan'] = 'Jurnal Nasional Terakreditasi';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$jurnal_4)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 25){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 25';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    // $validatedData['kode'] = $jurnal_4;
                    // $validatedData['nilai_kegiatan'] = 'Jurnal Nasional Terakreditasi';
                    // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }

                // jurnal 5a
                elseif($request->input('c_jurnal') ==  $jurnal_5a){
                    
                    $validatedData['kode'] = $jurnal_5a;
                    $validatedData['nilai_kegiatan'] = 'Jurnal Nasional Berbahasa Indonesia Terindek Pada DOAJ';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$jurnal_5a)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 15){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 15';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    // $validatedData['kode'] = $jurnal_5a;
                    // $validatedData['nilai_kegiatan'] = 'Jurnal Nasional Berbahasa Indonesia Terindek Pada DOAJ';
                    // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }


                // jurnal 5b
                elseif($request->input('c_jurnal') ==  $jurnal_5b){
                    
                    $validatedData['kode'] = $jurnal_5b;
                    $validatedData['nilai_kegiatan'] = 'Jurnal Nasional Berbahasa Inggris Atau Bahasa Resmi (PBB) Terindek Pada DOAJ';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$jurnal_5b)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 20){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 20';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    // $validatedData['kode'] = $jurnal_5b;
                    // $validatedData['nilai_kegiatan'] = 'Jurnal Nasional Berbahasa Inggris Atau Bahasa Resmi (PBB) Terindek Pada DOAJ';
                    // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }


                // jurnal 6
                elseif($request->input('c_jurnal') ==  $jurnal_6){
                    
                    $validatedData['kode'] = $jurnal_6;
                    $validatedData['nilai_kegiatan'] = 'Jurnal Nasional';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$jurnal_6)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 10){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 10';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    // $validatedData['kode'] = $jurnal_6;
                    // $validatedData['nilai_kegiatan'] = 'Jurnal Nasional';
                    // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }


                // jurnal 7
                elseif($request->input('c_jurnal') ==  $jurnal_7){
                    
                    $validatedData['kode'] = $jurnal_7;
                    $validatedData['nilai_kegiatan'] = 'Jurnal Ilmiah Yang Ditulis Dalam Bahasa Resmi PBB Namun Tidak Memenuhi Syarat-Syarat Sebagai Jurnal Ilmiah Internasional';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$jurnal_7)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // if($result > 10){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 10';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    // $validatedData['kode'] = $jurnal_7;
                    // $validatedData['nilai_kegiatan'] = 'Jurnal Ilmiah Yang Ditulis Dalam Bahasa Resmi PBB Namun Tidak Memenuhi Syarat-Syarat Sebagai Jurnal Ilmiah Internasional';
                    // $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }else{
                    abort(404);
                }

            }elseif($request->input('komponen_kegiatan') == 'Hasil Penelitian Atau Hasil Pemikiran Yang Dipublikasikan'){
                $validator = Validator::make($request->all(), [
                    'c_jurnal' => 'max:255|required',
                ],[
                    'c_jurnal.required' => 'Jurnal harus diisi',
                    'c_jurnal.max' => 'Jurnal tidak boleh lebih dari 255 karakter'
                ]);

                if ($validator->fails()) {
                    $errorMessage = 'Hasil Penelitian Atau Hasil Pemikiran Yang Dipublikasikan Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }
                

            }
            

        // 2 A Hasil penelitian atau hasil pemikiran yang didesiminasikan
            if ($request->filled('dipresentasikan_secara_oral') && $request->input('komponen_kegiatan') == 'Dipresentasikan Secara Oral Dan Dimuat Dalam Prosiding Yang Dipublikasikan (Ber ISSN/ISBN)'){
                // deklarasi variabel
                    $internasional = 'II.A.1.c.1.a.1';
                    $nasional = 'II.A.1.c.2.b';

                // validasi nilai yang masuk
                    #internasional
                    if($request->input('dipresentasikan_secara_oral') == $internasional){
                            // // validasi maksimal angka kredit 
                            // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                 ->where('kode',$internasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            //     if($result > 10){
                            //         $errorMessage = 'Maksimal Angka Kredit Yaitu 10';
                            //         Alert::error($errorMessage);
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            //     }

                            $validatedData['kode'] = $internasional;
                            $validatedData['nilai_kegiatan'] = 'Internasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Nasional
                    elseif($request->input('dipresentasikan_secara_oral') == $nasional){

                            // // validasi maksimal angka kredit 
                            // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                     ->where('kode',$nasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 5){
                            //     $errorMessage = 'Maksimal Angka Kredit Yaitu 5';
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $nasional;
                            $validatedData['nilai_kegiatan'] = 'Nasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }

            }elseif($request->input('komponen_kegiatan') == 'Dipresentasikan Secara Oral Dan Dimuat Dalam Prosiding Yang Dipublikasikan (Ber ISSN/ISBN)'){
                
                $validator = Validator::make($request->all(), [
                    'dipresentasikan_secara_oral' => 'max:255|required',
                ],[
                    'dipresentasikan_secara_oral.required' => 'Anda tidak mengisi Nilai Kegiatan Dipresentasikan secara oral dan dimuat dalam prosiding',
                    'dipresentasikan_secara_oral.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                if ($validator->fails()) {
                    $errorMessage = 'Nilai dari Dipresentasikan secara 	oral dan dimuat dalam prosiding yang dipublikasikan (ber ISSN/ISBN) Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }
            

            }

        // 2 B Hasil penelitian atau hasil pemikiran yang didesiminasikan
            if ($request->filled('disajikan_dalam_bentuk_poster') && $request->input('komponen_kegiatan') == 'Disajikan Dalam Bentuk Poster Dan Dimuat Dalam Prosiding Yang Dipublikasikan'){
                // deklarasi variabel
                $internasional = 'II.A.1.c.2.a';
                $nasional = 'II.A.1.c.1.b.1';

                // validasi nilai yang masuk
                    #internasional
                    if($request->input('disajikan_dalam_bentuk_poster') == $internasional){
                            // // validasi maksimal angka kredit 
                            // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                 ->where('kode',$internasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            //     if($result > 10){
                            //         $errorMessage = 'Maksimal Angka Kredit Yaitu 10';
                            //         Alert::error($errorMessage);
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            //     }

                            $validatedData['kode'] = $internasional;
                            $validatedData['nilai_kegiatan'] = 'Internasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Nasional
                    elseif($request->input('disajikan_dalam_bentuk_poster') == $nasional){

                            // // validasi maksimal angka kredit 
                            // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                     ->where('kode',$nasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 5){
                            //     $errorMessage = 'Maksimal Angka Kredit Yaitu 5';
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $nasional;
                            $validatedData['nilai_kegiatan'] = 'Nasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }
            }elseif($request->input('komponen_kegiatan') == 'Disajikan Dalam Bentuk Poster Dan Dimuat Dalam Prosiding Yang Dipublikasikan'){
                
                $validator = Validator::make($request->all(), [
                    'disajikan_dalam_bentuk_poster' => 'max:255|required',
                ],[
                    'disajikan_dalam_bentuk_poster.required' => 'Anda tidak mengisi Nilai Kegiatan Dalam bentuk Poster dan Dimuat Dalam Prosiding Yang Dipublikasikan',
                    'disajikan_dalam_bentuk_poster.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                if ($validator->fails()) {
                    $errorMessage = 'Nilai dari Kegiatan Disajikan dalam bentuk poster dan dimuat dalam	prosiding yang dipublikasikan Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }
            

            }


        // 2 C Hasil penelitian atau hasil pemikiran yang didesiminasikan
            if ($request->filled('disajikan_dalam_seminar') && $request->input('komponen_kegiatan') == 'Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan'){
                // deklarasi variabel
                $internasional = 'II.A.1.c.1.a';
                $nasional = 'II.A.1.c.1.b';

                // validasi nilai yang masuk
                    #internasional
                    if($request->input('disajikan_dalam_seminar') == $internasional){
                            // // validasi maksimal angka kredit 
                            // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                 ->where('kode',$internasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            //     if($result > 5){
                            //         $errorMessage = 'Maksimal Angka Kredit Yaitu 5';
                            //         Alert::error($errorMessage);
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            //     }

                        $validatedData['kode'] = $internasional;
                        $validatedData['nilai_kegiatan'] = 'Internasional';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Nasional
                    elseif($request->input('disajikan_dalam_seminar') == $nasional){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                     ->where('kode',$nasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 3){
                            //     $errorMessage = 'Maksimal Angka Kredit Yaitu 3';
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $nasional;
                            $validatedData['nilai_kegiatan'] = 'Nasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }
            }elseif($request->input('komponen_kegiatan') == 'Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan'){
                
                $validator = Validator::make($request->all(), [
                    'disajikan_dalam_seminar' => 'max:255|required',
                ],[
                    'disajikan_dalam_seminar.required' => 'Error',
                    'disajikan_dalam_seminar.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                if ($validator->fails()) {
                    $errorMessage = 'Nilai dari Kegiatan Disajikan Dalam Seminar/ Simposium/ Lokakarya, Tetapi Tidak Dimuat Dalam Posiding Yang Dipublikasikan Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }
            

            }


        // 2 D Hasil penelitian atau hasil pemikiran yang didesiminasikan
            if ($request->filled('tidak_disajikan_dalam_seminar') && $request->input('komponen_kegiatan') == 'Hasil Penelitian/ Pemikiran Yang Tidak Disajikan Dalam Seminar/ Dimposium/ Lokakarya, Tetapi Dimuat Dalam Prosiding'){
                // deklarasi variabel
                $internasional = 'II.A.1.c.3.a';
                $nasional = 'II.A.1.c.3.b';

                // validasi nilai yang masuk
                    #internasional
                    if($request->input('tidak_disajikan_dalam_seminar') == $internasional){
                            // // validasi maksimal angka kredit 
                            // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                 ->where('kode',$internasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            //     if($result > 10){
                            //         $errorMessage = 'Maksimal Angka Kredit Yaitu 10';
                            //         Alert::error($errorMessage);
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            //     }

                            $validatedData['kode'] = $internasional;
                            $validatedData['nilai_kegiatan'] = 'Internasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Nasional
                    elseif($request->input('tidak_disajikan_dalam_seminar') == $nasional){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                     ->where('kode',$nasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 5){
                            //     $errorMessage = 'Maksimal Angka Kredit Yaitu 5';
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $nasional;
                            $validatedData['nilai_kegiatan'] = 'Nasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }
            }elseif($request->input('komponen_kegiatan') == 'Hasil Penelitian/ Pemikiran Yang Tidak Disajikan Dalam Seminar/ Dimposium/ Lokakarya, Tetapi Dimuat Dalam Prosiding'){
                
                $validator = Validator::make($request->all(), [
                    'tidak_disajikan_dalam_seminar' => 'max:255|required',
                ],[
                    'tidak_disajikan_dalam_seminar.required' => 'Error',
                    'tidak_disajikan_dalam_seminar.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                if ($validator->fails()) {
                    $errorMessage = 'Nilai dari Hasil Penelitian/ Pemikiran Yang Tidak Disajikan Dalam Seminar/ Dimposium/ Lokakarya, Tetapi Dimuat Dalam Prosiding Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }
            

            }


        // 2 E Hasil penelitian atau hasil pemikiran yang didesiminasikan
            if ($request->input('komponen_kegiatan') == 'Hasil Penelitian/ Pemikiran/ Yang Disajikan Dalam Koran/ Majalah Populer/ Umum'){
                // deklarasi variabel
                $kode = 'II.A.1.d';

                // validasi nilai yang masuk
                        // validasi maksimal angka kredit 
                            // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                 ->where('kode',$kode)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            //     if($result > 1){
                            //         $errorMessage = 'Maksimal Angka Kredit Yaitu 1';
                            //         Alert::error($errorMessage);
                            //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            //     }

                $validatedData['kode'] = $kode;
                // $validatedData['nilai_kegiatan'] = '';
                $validatedData['angka_kredit'] = $request->input('angka_kredit');
                $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    

            }

        // 3 Hasil Penelitian Atau Pemikiran Atau Kerjasama Industri Yang Tidak Dipublikasikan (Tersimpan Dalam Perpustakaan)
            // if($request->input('komponen_kegiatan') == NULL && $request->input('tipe_kegiatan') == 'Hasil Penelitian Atau Pemikiran Atau Kerjasama Industri Yang Tidak Dipublikasikan (Tersimpan Dalam Perpustakaan)'){
            if($request->input('tipe_kegiatan') == 'Hasil Penelitian Atau Pemikiran Atau Kerjasama Industri Yang Tidak Dipublikasikan (Tersimpan Dalam Perpustakaan)'){
                    // deklarasi variable
                    $kode = 'II.A.2';

                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$kode)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // // cek maksimal angka kredit
                    // if($result > 2){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 2';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                $validatedData['kode'] = $kode;
                // $validatedData['nilai_kegiatan'] = '';
                $validatedData['angka_kredit'] = $request->input('angka_kredit');
                // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

            }

        // 4 Menerjemahkan/ Menyadur Buku Ilmiah Yang Diterbitkan (Ber ISBN)
            if($request->input('tipe_kegiatan') == 'Menerjemahkan/ Menyadur Buku Ilmiah Yang Diterbitkan (Ber ISBN)'){
                    // deklarasi variable
                    $kode = 'II.B';

                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$kode)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // // cek maksimal angka kredit
                    // if($result > 15){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 15';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                $validatedData['kode'] = $kode;
                // $validatedData['nilai_kegiatan'] = '';
                $validatedData['angka_kredit'] = $request->input('angka_kredit');
                // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

            }

        // 5 Mengedit/ Menyunting Karya Ilmiah Dalam Bentuk Buku Yang Diterbitkan (Ber ISBN)
            if($request->input('tipe_kegiatan') == 'Mengedit/ Menyunting Karya Ilmiah Dalam Bentuk Buku Yang Diterbitkan (Ber ISBN)'){
                    // deklarasi variable
                    $kode = 'II.C';

                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                    //                                             ->where('kode',$kode)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // // cek maksimal angka kredit
                    // if($result > 10){
                    //     $errorMessage = 'Maksimal Angka Kredit Yaitu 10';
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                $validatedData['kode'] = $kode;
                // $validatedData['nilai_kegiatan'] = '';
                $validatedData['angka_kredit'] = $request->input('angka_kredit');
                // $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

            }

        // 6 Membuat Rancangan Dan Karya Teknologi/ Seni Yang Dipatenkan Secara Nasional Atau Internasional
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Membuat Rancangan Dan Karya Teknologi/ Seni Yang Dipatenkan Secara Nasional Atau Internasional' &&
                ($request->input('komponen_kegiatan') == 'Internasional (Paling Sedikit Diakui Oleh 4 Negara)' || $request->input('komponen_kegiatan') == 'Nasional')
                ) {
                    // deklarasi variabel
                    $internasional = 'Internasional (Paling Sedikit Diakui Oleh 4 Negara)';
                    $nasional = 'Nasional';
                    $kode_internasional = 'II.D.1';
                    $kode_nasional = 'II.D.2';

                // validasi nilai yang masuk
                    #internasional
                    if($request->input('komponen_kegiatan') == $internasional){
                                // // validasi maksimal angka kredit 
                                // $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                                //                                             ->where('kode',$kode_internasional)->sum('angka_kredit');

                                // $result = $count_angka_kredit + $request->input('angka_kredit');

                                // // cek maksimal angka kredit
                                // if($result > 60){
                                //     $errorMessage = 'Maksimal Angka Kredit Yaitu 60';
                                //     Alert::error($errorMessage);
                                //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                // }

                            $validatedData['kode'] = $kode_internasional;
                            // $validatedData['nilai_kegiatan'] = $internasional;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Nasional
                    elseif($request->input('komponen_kegiatan') == $nasional){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                     ->where('kode',$kode_nasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 40){
                            //     $errorMessage = 'Maksimal Angka Kredit Yaitu 40';
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $kode_nasional;
                            // $validatedData['nilai_kegiatan'] = 'Nasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }
            }
        // 7 Membuat Rancangan Dan Karya Teknologi Yang Tidak Dipatenkan; Rancangan Dan Karya Seni Monumental/ Seni Pertunjukan; Karya Sastra
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Membuat Rancangan Dan Karya Teknologi Yang Tidak Dipatenkan; Rancangan Dan Karya Seni Monumental/ Seni Pertunjukan; Karya Sastra' 
                ) {
                    // deklarasi variabel
                    $internasional = 'Tingkat Internasional';
                    $nasional = 'Tingkat Nasional';
                    $lokal = 'Tingkat Lokal';
                    $kode_internasional = 'II.E.1';
                    $kode_nasional = 'II.E.2';
                    $kode_lokal = 'II.E.3';

                // validasi nilai yang masuk
                    #internasional
                    if($request->input('komponen_kegiatan') == $internasional){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                        //                                                     ->where('kode',$kode_internasional)->sum('angka_kredit');

                        //     $result = $count_angka_kredit + $request->input('angka_kredit');

                        //     // cek maksimal angka kredit
                        //         if($result > 20){
                        //             $errorMessage = 'Maksimal Angka Kredit Yaitu 20';
                        //             Alert::error($errorMessage);
                        //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //         }

                            $validatedData['kode'] = $kode_internasional;
                            // $validatedData['nilai_kegiatan'] = $internasional;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Nasional
                    elseif($request->input('komponen_kegiatan') == $nasional){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                            //                                                     ->where('kode',$kode_nasional)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 15){
                            //     $errorMessage = 'Maksimal Angka Kredit Yaitu 15';
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $kode_nasional;
                            // $validatedData['nilai_kegiatan'] = 'Nasional';
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }
                    # Lokal
                    elseif($request->input('komponen_kegiatan') == $lokal){

                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',2)
                        //                                                     ->where('kode',$kode_lokal)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // // cek maksimal angka kredit
                        // if($result > 10){
                        //     $errorMessage = 'Maksimal Angka Kredit Yaitu 10';
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        $validatedData['kode'] = $kode_lokal;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');


                    }


            }

         // 8 Membuat Rancangan Dan Karya Seni/ Seni Pertunjukan Yang Tidak Mendapatkan HKI
            if($request->input('tipe_kegiatan') == 'Membuat Rancangan Dan Karya Seni/ Seni Pertunjukan Yang Tidak Mendapatkan HKI'){
                // deklarasi variable
                $kode = 'II.E.4';

                $validatedData['kode'] = $kode;
                $validatedData['angka_kredit'] = $request->input('angka_kredit');


            }


        // ubah nama slug 
        $slug = Str::slug($request->input('kegiatan'));
        $random_token = Str::random(5);
        $slug_name = $slug.'-'. $random_token;

        // Handle upload bukti pdf
        $namadosen = auth()->user()->name;
        $direktori_dosen = Str::slug($namadosen);
        $nama_bukti = Str::slug($request->input('kegiatan'));
    
        $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/'. $direktori_dosen, time().'-'.$nama_bukti.'.pdf');

        $validatedData['slug'] = $slug_name;
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['kategori_pak_id'] = 2;

        // Create 
        pak_kegiatan_penelitian::create($validatedData);
    
        Alert::success('Berhasil','Menambahkan Kegiatan');
        return redirect()->route('penelitian');

    }

    public function penelitian_detail($detail){
        
        // Retrieve the record from the table based on the slug
        $record = pak_kegiatan_penelitian::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        return view('dosen.simulasi.penelitian.detail',[
            'title' => 'Penelitian',
            'record' => $record,
        ]);

    }

    public function penelitian_destroy($slug){
          // Retrieve the record from the table based on the slug
          $record = pak_kegiatan_penelitian::where('slug', $slug)->first();

          if (!$record) {
              // Handle the case where the record is not found
              abort(404);
          }
  
          if($record->bukti){
              Storage::delete($record->bukti);
          }
          
  
          pak_kegiatan_penelitian::destroy($record->id);
  
          Alert::success('Sukses','File Berhasil dihapus');
          return redirect()->route('penelitian');
    }


    public function penelitian_edit_store($detail, Request $request){
        // Retrieve the record from the table based on the slug
            $record = pak_kegiatan_penelitian::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }


        $validator = Validator::make($request->all(),[
            'kegiatan' => 'max:255',
            'bukti' => 'mimes:pdf|max:1024|',
            'angka_kredit' => 'max:255',
        ],[
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'kegiatan.regex' => 'Nama Kegiatan hanya boleh mengandung huruf dan spasi',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
        ]);

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Mengedit Data');
        }

        // simpan data
        $validatedData = $validator->validated();

        if ($request->input('kegiatan') == NULL || $request->input('angka_kredit') == NULL){
            $validator = Validator::make($request->all(),[
                'kegiatan' => 'required',
                'angka_kredit' => 'required',
            ],[
                'kegiatan.required' => 'Kegiatan harus diisi',
                'angka_kredit.required' => 'Angka Kredit harus diisi',
            ]);

            if ($validator->fails()) {
                Alert::error($validator->errors()->all()[0]);
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }


        if (!$request->hasFile('bukti')) {
            // Handle the case where the user didn't provide a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            // Use Storage::copy to copy the existing file to the desired directory
            $newFilePath = 'dosen/' . $direktori_dosen . '/' . time() . '-' . $nama_bukti . '.pdf';
            Storage::copy($record->bukti, $newFilePath);
        
            $validatedData['bukti'] = $newFilePath;
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        } else {
            // Handle the case where the user provided a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/' . $direktori_dosen, time() . '-' . $nama_bukti . '.pdf');
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        }
        
    
        // Create 
            pak_kegiatan_penelitian::where('slug', $detail)->update($validatedData);

            Alert::success('Berhasil','Update Data');
            return redirect()->back();

    }


    # Pengabdian Pada Masyarakat
    ############################################
    public function pengabdian_pada_masyarakat(){
    
        return view('dosen.simulasi.pengabdian_pada_masyarakat.index',[
            'title' => 'Simulasi PAK Pengabdian Pada Masyarakat',
            'all' => pak_kegiatan_pengabdian_pada_masyarakat::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {
                                                                            $query->where('now', true);
                                                                    })
                                                            ->where('user_id', auth()->user()->id)->where('kategori_pak_id', 3)
                                                            ->orderBy('id', 'DESC')
                                                            ->get(),
            'tahun_ajaran' => tahun_ajaran::where('now', true)->get(),
            'total_kredit' => pak_kegiatan_pengabdian_pada_masyarakat::QueryTotalAK()->sum('angka_kredit'),
        ]);
    }

    public function pengabdian_pada_masyarakat_tambah(){

        $tipe_kegiatan = tipe_kegiatan_pengabdian_pada_masyarakat::all();
        
        return view('dosen\simulasi\pengabdian_pada_masyarakat\tambah',[
            'title' => 'Pengabdian Pada Masyarakat',
            'tipe_kegiatan' => $tipe_kegiatan,
            't_a' => tahun_ajaran::where('now', 1)->value('tahun'),
            'tahun_ajaran_hidden' =>  tahun_ajaran::where('now',1)->first(),
            'semester' => tahun_ajaran::where('now', 1)->value('semester'),
        ]);

    }

    public function pengabdian_pada_masyarakat_tambah_store(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'kegiatan' => 'required|max:255|',
            'tipe_kegiatan' => 'required|max:255',
            'tahun_ajaran_id' => 'required' ,
            'angka_kredit' => 'required' ,
            'bukti' => 'required|max:1024|mimes:pdf',
            'tempat' => 'required|max:255',
            'tanggal_pelaksanaan' => 'required|date'
        ],[
            'kegiatan.required' => 'Nama Kegiatan Harus diisi',
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'tipe_kegiatan.required' => 'Tipe kegiatan harus diisi',
            'tipe_kegiatan.max' => 'Maksimal 255 karakter',
            'tahun_ajaran.required' => 'Tahun Ajaran harus diisi',
            'angka_kredit.required' => 'Angka Kredit Harus Diisi',
            'angka_kredit.numeric' => 'Angka Kredit Hanya Mengandung Angka',
            'bukti.required' => 'Bukti harus diupload',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
            'tempat.required' => 'Tempat Pelaksanaan Harus Diisi',
            'tempat.max' => 'Maksimal Tempat Pelaksaan 255 Karakter',
            'tanggal_pelaksanaan.required' => 'Tanggal Pelaksanaan Harus Diisi'
        ]);
    

        if($request->input('tipe_kegiatan') == 'default'){
            $errorMessage = 'Tipe Kegiatan Harus Diisi';
            Alert::error($errorMessage);
            return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        }

          // deklarasi variable yang memiliki komponen
        $punya_komponen = $request->input('tipe_kegiatan_id') == 3 || $request->input('tipe_kegiatan_id') == 4 ; 

        if(($punya_komponen) && $request->input('komponen_kegiatan') == NULL){
            $errorMessage = 'Anda Tidak Memilih Komponen Kegiatan';
            Alert::error($errorMessage);
            return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        }

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Menambahkan Kegiatan');
        }


        // simpan data
        $validatedData = $validator->validated();

     // 1 Menduduki Jabatan Pimpinan Pada Lembaga Pemerintahan/ Pejabat Negara Yang Harus Dibebaskan Dari Jabatan Organiknya Tiap Semester
        if($request->input('tipe_kegiatan') == 'Menduduki Jabatan Pimpinan Pada Lembaga Pemerintahan/ Pejabat Negara Yang Harus Dibebaskan Dari Jabatan Organiknya Tiap Semester'){
            // deklarasi variable
            $kode = '7.1';

            // $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
            //                                                                 ->where('kode',$kode)->sum('angka_kredit');

            // $result = $count_angka_kredit + $request->input('angka_kredit');

            //     if($result > 5.5){
            //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 5.5 . Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
            //         Alert::error($errorMessage);
            //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
            //     }


            $validatedData['kode'] = $kode;
            $validatedData['angka_kredit'] = $request->input('angka_kredit');
        }

        // 2 Melaksanakan Pengembangan Hasil Pendidikan, Dan Penelitian Yang Dapat Dimanfaatkan Oleh Masyarakat/ Industry Setiap Program
            if($request->input('tipe_kegiatan') == 'Melaksanakan Pengembangan Hasil Pendidikan, Dan Penelitian Yang Dapat Dimanfaatkan Oleh Masyarakat/ Industry Setiap Program'){
                // deklarasi variable
                $kode = '7.2';

                // $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                //                                                                 ->where('kode',$kode)->sum('angka_kredit');

                // $result = $count_angka_kredit + $request->input('angka_kredit');

                //     if($result > 3){
                //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3 . Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                //         Alert::error($errorMessage);
                //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                //     }


                $validatedData['kode'] = $kode;
                $validatedData['angka_kredit'] = $request->input('angka_kredit');
            }

        // 3 a Memberikan Latihan/ Penyuluhan/ Penataran/ Ceramah Pada Masyarakat, Terjadwal/ Terprogram
                // Dalam Satu Semester Atau Lebih
                if ($request->filled('satu_semester_atau_lebih') && $request->input('komponen_kegiatan') == 'Dalam Satu Semester Atau Lebih'){
                    // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
                
                    // declare the variable
                    $internasional = '7.3.1.a';
                    $nasional = '7.3.1.b';
                    $lokal = '7.3.1.c';

                    # Internasional
                    if($request->input('satu_semester_atau_lebih') == $internasional){
                        // validasi maksimal angka kredit 
                        // $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                 ->where('kode',$internasional)->sum('angka_kredit');
                        
                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        //     if($result > 4){
                        //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 4. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        $validatedData['kode'] = $internasional;
                        $validatedData['nilai_kegiatan'] = 'Tingkat Internasional Tiap Program';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        
                    }
                    # Nasional
                    elseif($request->input('satu_semester_atau_lebih') == $nasional){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                 ->where('kode',$nasional)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');
            

                        //     if($result > 3){
                        //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        $validatedData['kode'] = $nasional;
                        $validatedData['nilai_kegiatan'] = 'Tingkat Nasional, Tiap Program';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        
                    }
                    # Lokal
                    elseif($request->input('satu_semester_atau_lebih') == $lokal){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                 ->where('kode',$lokal)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');
            

                        //     if($result > 2){
                        //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        $validatedData['kode'] = $lokal;
                        $validatedData['nilai_kegiatan'] = 'Tingkat Lokal, Tiap Program';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        

                    }

                }elseif($request->input('komponen_kegiatan') == 'Dalam Satu Semester Atau Lebih'){
                    $validator = Validator::make($request->all(), [
                        'satu_semester_atau_lebih' => 'max:255|required',
                    ],[
                        'satu_semester_atau_lebih.required' => 'Error',
                        'satu_semester_atau_lebih.max' => 'Tidak boleh lebih dari 255 karakter'
                    ]);

                    // error
                    if ($validator->fails()) {
                        // Alert::error($validator->errors()->first());
                        // return redirect()->back()->withInput()->withErrors($validator);

                        $errorMessage = 'Nilai dari Memberikan Latihan/ Penyuluhan/ Penataran/ Ceramah Pada Masyarakat, Terjadwal/ Terprogram Harus Diisi';
                        $validator->errors()->add('buat_error', $errorMessage);
                        
                        Alert::error($validator->errors()->first());
                        return redirect()->back()->withInput()->withErrors($validator);
                    }


                }

        // 3 b Memberikan Latihan/ Penyuluhan/ Penataran/ Ceramah Pada Masyarakat, Terjadwal/ Terprogram
                // Kurang Dari Satu Semester Dan Minimal Satu Bulan
                if ($request->filled('kurang_satu_semester') && $request->input('komponen_kegiatan') == 'Kurang Dari Satu Semester Dan Minimal Satu Bulan'){
                    // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
                
                    // declare the variable
                    $internasional = '7.3.2.a';
                    $nasional = '7.3.2.b';
                    $lokal = '7.3.2.c';
                    $insidental = '7.3.2.d';

                    # Internasional
                    if($request->input('kurang_satu_semester') == $internasional){
                        // // validasi maksimal angka kredit 
                        // $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                 ->where('kode',$internasional)->sum('angka_kredit');
                        
                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        //     if($result > 3){
                        //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        $validatedData['kode'] = $internasional;
                        $validatedData['nilai_kegiatan'] = 'Tingkat Internasional Tiap Program';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        
                    }
                    # Nasional
                    elseif($request->input('kurang_satu_semester') == $nasional){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                 ->where('kode',$nasional)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');
            

                        //     if($result > 2){
                        //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        $validatedData['kode'] = $nasional;
                        $validatedData['nilai_kegiatan'] = 'Tingkat Nasional, Tiap Program';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        
                    }
                    # Lokal
                    elseif($request->input('kurang_satu_semester') == $lokal){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                 ->where('kode',$lokal)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');
            

                        //     if($result > 1){
                        //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        $validatedData['kode'] = $lokal;
                        $validatedData['nilai_kegiatan'] = 'Tingkat Lokal, Tiap Program';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }
                    # Insidental
                    elseif($request->input('kurang_satu_semester') == $insidental){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                 ->where('kode',$insidental)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');
            

                        //     if($result > 1){
                        //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //         Alert::error($errorMessage);
                        //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //     }

                        $validatedData['kode'] = $insidental;
                        $validatedData['nilai_kegiatan'] = 'Insidental, Tiap Kegiatan/ Program ';
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }



                }elseif($request->input('komponen_kegiatan') == 'Kurang Dari Satu Semester Dan Minimal Satu Bulan'){
                    $validator = Validator::make($request->all(), [
                        'kurang_satu_semester' => 'max:255|required',
                    ],[
                        'kurang_satu_semester.required' => 'Error',
                        'kurang_satu_semester.max' => 'Tidak boleh lebih dari 255 karakter'
                    ]);

                    // error
                    if ($validator->fails()) {
                        // Alert::error($validator->errors()->first());
                        // return redirect()->back()->withInput()->withErrors($validator);

                        $errorMessage = 'Nilai dari Memberikan Latihan/ Penyuluhan/ Penataran/ Ceramah Pada Masyarakat, Terjadwal/ Terprogram Harus Diisi';
                        $validator->errors()->add('buat_error', $errorMessage);
                        
                        Alert::error($validator->errors()->first());
                        return redirect()->back()->withInput()->withErrors($validator);
                    }


                }

        // 4 Memberi Pelayanan Kepada Masyarakat Atau Kegiatan Lain Yang Menunjang Pelaksanaan Tugas Pemerintahan Dan Pembangunan
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Memberi Pelayanan Kepada Masyarakat Atau Kegiatan Lain Yang Menunjang Pelaksanaan Tugas Pemerintahan Dan Pembangunan') {
                    // deklarasi variabel
                    $keahlian = 'Berdasarkan Bidang Keahlian, Tiap Program';
                    $penugasan = 'Berdasarkan Penugasan Lembaga Terguruan Tinggi, Tiap Program';
                    $fungsi = 'Berdasarkan Fungsi/ Jabatan Tiap Program';
                    $kode_keahlian = '7.4.a';
                    $kode_penugasan = '7.4.b';
                    $kode_fungsi = '7.4.c';

                    # Keahlian
                    if($request->input('komponen_kegiatan') == $keahlian){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                        //                                                     ->where('kode',$kode_keahlian)->sum('angka_kredit');

                        //     $result = $count_angka_kredit + $request->input('angka_kredit');

                        //     // cek maksimal angka kredit
                        //         if($result > 1.5){
                        //             $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1.5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //             Alert::error($errorMessage);
                        //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //         }

                            $validatedData['kode'] = $kode_keahlian;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Penugasan
                    elseif($request->input('komponen_kegiatan') == $penugasan){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                            //                                                     ->where('kode',$kode_penugasan)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 1){
                            //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $kode_penugasan;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }
                    # Fungsi
                    elseif($request->input('komponen_kegiatan') == $fungsi){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                            //                                                     ->where('kode',$kode_fungsi)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 0.5){
                            //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 0.5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $kode_fungsi;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }
            }

        // 5 Membuat/ Menulis Karya Pengabdian Pada Masyarakat Yang Tidak Dipublikasikan, Tiap Karya
            if($request->input('tipe_kegiatan') == 'Membuat/ Menulis Karya Pengabdian Pada Masyarakat Yang Tidak Dipublikasikan, Tiap Karya'){
                // deklarasi variable
                $kode = '7.5';

                // $count_angka_kredit = pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->where('kategori_pak_id',3)
                //                                                                 ->where('kode',$kode)->sum('angka_kredit');

                // $result = $count_angka_kredit + $request->input('angka_kredit');

                //     if($result > 3){
                //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3 . Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                //         Alert::error($errorMessage);
                //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                //     }


                $validatedData['kode'] = $kode;
                $validatedData['angka_kredit'] = $request->input('angka_kredit');
            }


            // ubah nama slug 
                $slug = Str::slug($request->input('kegiatan'));
                $random_token = Str::random(5);
                $slug_name = $slug.'-'. $random_token;
            
            // Handle upload bukti pdf
                $namadosen = auth()->user()->name;
                $direktori_dosen = Str::slug($namadosen);
                $nama_bukti = Str::slug($request->input('kegiatan'));

            $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/'. $direktori_dosen, time().'-'.$nama_bukti.'.pdf');
            
            $validatedData['slug'] = $slug_name;
            $validatedData['user_id'] = auth()->user()->id;
            $validatedData['kategori_pak_id'] = 3;
            
        // Create 
            pak_kegiatan_pengabdian_pada_masyarakat::create($validatedData);
            
            Alert::success('Berhasil','Menambahkan Kegiatan');
            return redirect()->route('pengabdian-pada-masyarakat');



    }

    public function pengabdian_pada_masyarakat_destroy($slug){
        // Retrieve the record from the table based on the slug
        $record = pak_kegiatan_pengabdian_pada_masyarakat::where('slug', $slug)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        if($record->bukti){
            Storage::delete($record->bukti);
        }
        
        pak_kegiatan_pengabdian_pada_masyarakat::destroy($record->id);

        Alert::success('Sukses','File Berhasil dihapus');
        return redirect()->route('pengabdian-pada-masyarakat');
    }

    public function pengabdian_pada_masyarakat_detail($detail){
        
        // Retrieve the record from the table based on the slug
        $record = pak_kegiatan_pengabdian_pada_masyarakat::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        return view('dosen.simulasi.pengabdian_pada_masyarakat.detail',[
            'title' => 'Pengabdian Pada Masyarakat',
            'record' => $record,
        ]);

    }

    public function pengabdian_pada_masyarakat_edit_store($detail, Request $request){
        // Retrieve the record from the table based on the slug
            $record = pak_kegiatan_pengabdian_pada_masyarakat::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }


        $validator = Validator::make($request->all(),[
            'kegiatan' => 'max:255',
            'bukti' => 'mimes:pdf|max:1024|',
            'angka_kredit' => 'max:255',
            'tempat' => 'max:255',
            'tanggal_pelaksanaan' => 'date'
        ],[
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'kegiatan.regex' => 'Nama Kegiatan hanya boleh mengandung huruf dan spasi',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
        ]);

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Mengedit Data');
        }

        // simpan data
        $validatedData = $validator->validated();

        if ($request->input('kegiatan') == NULL || $request->input('angka_kredit') == NULL ||
            $request->input('tempat') == NULL || $request->input('tanggal_pelaksanaan') == NULL
        ){
            $validator = Validator::make($request->all(),[
                'kegiatan' => 'required',
                'angka_kredit' => 'required',
                'tempat' => 'required',
                'tanggal_pelaksanaan' => 'required',
            ],[
                'kegiatan.required' => 'Kegiatan harus diisi',
                'angka_kredit.required' => 'Angka Kredit harus diisi',
                'tempat.required' => 'Tempat harus diisi',
                'tanggal_pelaksanaan.required' => 'Tanggal Pelaksanaan harus diisi',
            ]);

            if ($validator->fails()) {
                Alert::error($validator->errors()->all()[0]);
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }


        if (!$request->hasFile('bukti')) {
            // Handle the case where the user didn't provide a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            // Use Storage::copy to copy the existing file to the desired directory
            $newFilePath = 'dosen/' . $direktori_dosen . '/' . time() . '-' . $nama_bukti . '.pdf';
            Storage::copy($record->bukti, $newFilePath);
        
            $validatedData['bukti'] = $newFilePath;
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        } else {
            // Handle the case where the user provided a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/' . $direktori_dosen, time() . '-' . $nama_bukti . '.pdf');
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        }
        
    
        // Create 
            pak_kegiatan_pengabdian_pada_masyarakat::where('slug', $detail)->update($validatedData);

            Alert::success('Berhasil','Update Data');
            return redirect()->back();

    }

    
    # Penunjang Tri Dharma Pt 
    ############################################

    public function penunjang_tri_dharma_pt(){

        return view('dosen.simulasi.penunjang_tri_dharma_pt.index',[
            'title' => 'Simulasi Pendidikan dan Pengajaran',
            'all' => pak_kegiatan_penunjang_tri_dharma_pt::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {
                                                                            $query->where('now', true);
                                                                    })
                                                            ->where('user_id', auth()->user()->id)->where('kategori_pak_id', 4)
                                                            ->orderBy('id', 'DESC')
                                                            ->get(),
            'tahun_ajaran' => tahun_ajaran::where('now', true)->get(),
            'total_kredit' => pak_kegiatan_penunjang_tri_dharma_pt::QueryTotalAK()->sum('angka_kredit'),
        ]);
    }

    public function penunjang_tri_dharma_pt_tambah(){

        $tipe_kegiatan = tipe_kegiatan_penunjang_tri_dharma_pt::all();
        
        return view('dosen\simulasi\penunjang_tri_dharma_pt\tambah',[
            'title' => 'Simulasi Penunjang Tri Dharma PT',
            'tipe_kegiatan' => $tipe_kegiatan,
            't_a' => tahun_ajaran::where('now', 1)->value('tahun'),
            'tahun_ajaran_hidden' =>  tahun_ajaran::where('now',1)->first(),
            'semester' => tahun_ajaran::where('now', 1)->value('semester'),
        ]);

    }

    public function penunjang_tri_dharma_pt_tambah_store(Request $request){
        
        // dd($request->all());
        $validator = Validator::make($request->all(),[
            'kegiatan' => 'required|max:255|',
            'tipe_kegiatan' => 'required|max:255',
            'tahun_ajaran_id' => 'required' ,
            'angka_kredit' => 'required' ,
            'bukti' => 'required|max:1024|mimes:pdf',
            'tempat' => 'required|max:255',
            'tanggal_pelaksanaan' => 'required|date'
        ],[
            'kegiatan.required' => 'Nama Kegiatan Harus diisi',
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'tipe_kegiatan.required' => 'Tipe kegiatan harus diisi',
            'tipe_kegiatan.max' => 'Maksimal 255 karakter',
            'tahun_ajaran.required' => 'Tahun Ajaran harus diisi',
            'angka_kredit.required' => 'Angka Kredit Harus Diisi',
            'angka_kredit.numeric' => 'Angka Kredit Hanya Mengandung Angka',
            'bukti.required' => 'Bukti harus diupload',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
            'tempat.required' => 'Tempat Pelaksanaan Harus Diisi',
            'tempat.max' => 'Maksimal Tempat Pelaksaan 255 Karakter',
            'tanggal_pelaksanaan.required' => 'Tanggal Pelaksanaan Harus Diisi'
        ]);
    

        if($request->input('tipe_kegiatan') == 'default'){
            $errorMessage = 'Tipe Kegiatan Harus Diisi';
            Alert::error($errorMessage);
            return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        }

          // deklarasi variable yang memiliki komponen
        $punya_komponen = $request->input('tipe_kegiatan_id') == 1 || $request->input('tipe_kegiatan_id') == 2 || 
                            $request->input('tipe_kegiatan_id') == 3 || $request->input('tipe_kegiatan_id') == 5 ||
                            $request->input('tipe_kegiatan_id') == 6 || $request->input('tipe_kegiatan_id') == 7 ||
                            $request->input('tipe_kegiatan_id') == 8 || $request->input('tipe_kegiatan_id') == 9 ||
                            $request->input('tipe_kegiatan_id') == 10 ; 

        if(($punya_komponen) && $request->input('komponen_kegiatan') == NULL){
            $errorMessage = 'Anda Tidak Memilih Komponen Kegiatan';
            Alert::error($errorMessage);
            return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
        }

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Menambahkan Kegiatan');
        }


        // simpan data
        $validatedData = $validator->validated();

        // 1 Menjadi Anggota Dalam Suatu Panitia/ Badan Pada Perguruan Tinggi
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan_id') == 1 
                ) {
                    // deklarasi variabel
                    $ketua = 'Sebagai Ketua/ Wakil Ketua Merangkap Anggota, Tiap Tahun';
                    $anggota = 'Sebagai Anggota, Tiap Tahun';
                    $kode_ketua = '8.1.a';
                    $kode_anggota = '8.1.b';

                    #ketua
                    if($request->input('komponen_kegiatan') == $ketua){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_ketua)->sum('angka_kredit');

                        //     $result = $count_angka_kredit + $request->input('angka_kredit');

                        //     // cek maksimal angka kredit
                        //         if($result > 3){
                        //             $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //             Alert::error($errorMessage);
                        //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //         }

                            $validatedData['kode'] = $kode_ketua;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # Anggota
                    elseif($request->input('komponen_kegiatan') == $anggota){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                            //                                                     ->where('kode',$kode_anggota)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 2){
                            //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah ' . $count_angka_kredit;
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $kode_anggota;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }
            }
        
        // 2 a Menjadi Anggota Panitia/ Badan Pada Lembaga Pemerintah
            // Panitia Pusat, Sebagai
            if ($request->filled('panitia_pusat') && $request->input('komponen_kegiatan') == 'Panitia Pusat, Sebagai'){
                // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
            
                // declare the variable
                $ketua = '8.2.a.1';
                $anggota = '8.2.a.2';

                # Ketua
                if($request->input('panitia_pusat') == $ketua){
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$ketua)->sum('angka_kredit');
                    
                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     if($result > 3){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $ketua;
                    $validatedData['nilai_kegiatan'] = 'Ketua/ Wakil Ketua, Tiap Kepanitiaan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                
                # Anggota
                }elseif($request->input('panitia_pusat') == $anggota){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 2){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota;
                    $validatedData['nilai_kegiatan'] = 'Anggota, Tiap Kepanitiaan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    

                }

            }elseif($request->input('komponen_kegiatan') == 'Panitia Pusat, Sebagai'){
                $validator = Validator::make($request->all(), [
                    'panitia_pusat' => 'max:255|required',
                ],[
                    'panitia_pusat.required' => 'Error',
                    'panitia_pusat.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {
                    // Alert::error($validator->errors()->first());
                    // return redirect()->back()->withInput()->withErrors($validator);

                    $errorMessage = 'Nilai dari Kegiatan Menjadi Anggota Panitia/ Badan Pada Lembaga Pemerintah Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }


            }

        // 2 b Menjadi Anggota Panitia/ Badan Pada Lembaga Pemerintah
            // Panitia Daerah, Sebagai
            if ($request->filled('panitia_daerah') && $request->input('komponen_kegiatan') == 'Panitia Daerah, Sebagai'){
                // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
            
                // declare the variable
                $ketua = '8.2.b.1';
                $anggota = '8.2.b.2';

                # Ketua
                if($request->input('panitia_daerah') == $ketua){
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$ketua)->sum('angka_kredit');
                    
                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     if($result > 2){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $ketua;
                    $validatedData['nilai_kegiatan'] = 'Ketua/ Wakil Ketua, Tiap Kepanitiaan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                
                # Anggota
                }elseif($request->input('panitia_daerah') == $anggota){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 1){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota;
                    $validatedData['nilai_kegiatan'] = 'Anggota, Tiap Kepanitiaan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    

                }

            }elseif($request->input('komponen_kegiatan') == 'Panitia Daerah, Sebagai'){
                $validator = Validator::make($request->all(), [
                    'panitia_daerah' => 'max:255|required',
                ],[
                    'panitia_daerah.required' => 'Error',
                    'panitia_daerah.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {
                    // Alert::error($validator->errors()->first());
                    // return redirect()->back()->withInput()->withErrors($validator);

                    $errorMessage = 'Nilai dari Kegiatan Menjadi Anggota Panitia/ Badan Pada Lembaga Pemerintah Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }


            }

        // 3 a Menjadi Anggota Organisasi Profesi
            // Tingkat Internasional, Sebagai
            if ($request->filled('tingkat_internasional') && $request->input('komponen_kegiatan') == 'Tingkat Internasional, Sebagai'){
                // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
            
                // declare the variable
                $pengurus = '8.3.a.1';
                $anggota_permintaan = '8.3.a.2';
                $anggota = '8.3.a.3';

                # pengurus
                if($request->input('tingkat_internasional') == $pengurus){
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$pengurus)->sum('angka_kredit');
                    
                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     if($result > 2){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $pengurus;
                    $validatedData['nilai_kegiatan'] = 'Pengurus, Tiap Periode Jabatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                }
                # Anggota Atas Permintaan
                elseif($request->input('tingkat_internasional') == $anggota_permintaan){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota_permintaan)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 1){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota_permintaan;
                    $validatedData['nilai_kegiatan'] = 'Anggota Atas Permintaan, Tiap Periode Jabatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                }
                # Anggota Atas Permintaan
                elseif($request->input('tingkat_internasional') == $anggota){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 0.5){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 0.5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota;
                    $validatedData['nilai_kegiatan'] = 'Anggota, Tiap Periode Jabatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    

                }

            }elseif($request->input('komponen_kegiatan') == 'Tingkat Internasional, Sebagai'){
                $validator = Validator::make($request->all(), [
                    'tingkat_internasional' => 'max:255|required',
                ],[
                    'tingkat_internasional.required' => 'Error',
                    'tingkat_internasional.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {
                    // Alert::error($validator->errors()->first());
                    // return redirect()->back()->withInput()->withErrors($validator);

                    $errorMessage = 'Nilai dari Kegiatan Menjadi Anggota Organisasi Profesi Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }


            }

        // 3 b Menjadi Anggota Organisasi Profesi
            // Tingkat Nasional, Sebagai
            if ($request->filled('tingkat_nasional') && $request->input('komponen_kegiatan') == 'Tingkat Nasional, Sebagai'){
                // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
            
                // declare the variable
                $pengurus = '8.3.b.1';
                $anggota_permintaan = '8.3.b.2';
                $anggota = '8.3.b.3';

                # pengurus
                if($request->input('tingkat_nasional') == $pengurus){
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$pengurus)->sum('angka_kredit');
                    
                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     if($result > 1.5){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1.5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $pengurus;
                    $validatedData['nilai_kegiatan'] = 'Pengurus, Tiap Periode Jabatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                }
                # Anggota Atas Permintaan
                elseif($request->input('tingkat_nasional') == $anggota_permintaan){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota_permintaan)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 1){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota_permintaan;
                    $validatedData['nilai_kegiatan'] = 'Anggota Atas Permintaan, Tiap Periode Jabatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                }
                # Anggota Atas Permintaan
                elseif($request->input('tingkat_nasional') == $anggota){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 0.5){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 0.5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota;
                    $validatedData['nilai_kegiatan'] = 'Anggota, Tiap Periode Jabatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    

                }

            }elseif($request->input('komponen_kegiatan') == 'Tingkat Nasional, Sebagai'){
                $validator = Validator::make($request->all(), [
                    'tingkat_nasional' => 'max:255|required',
                ],[
                    'tingkat_nasional.required' => 'Error',
                    'tingkat_nasional.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {

                    $errorMessage = 'Nilai dari Kegiatan Menjadi Anggota Organisasi Profesi Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }


            }

        // 4 Mewakili Perguruan Tinggi/ Lembaga Pemerintah Duduk Dalam Panitia Antar Lembaga, Tiap Kepanitiaan
            if($request->input('tipe_kegiatan') == 'Mewakili Perguruan Tinggi/ Lembaga Pemerintah Duduk Dalam Panitia Antar Lembaga, Tiap Kepanitiaan'){
                // deklarasi variable
                $kode = '8.4';

                // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                //                                                                 ->where('kode',$kode)->sum('angka_kredit');

                // $result = $count_angka_kredit + $request->input('angka_kredit');

                //     if($result > 1){
                //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                //         Alert::error($errorMessage);
                //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                //     }


                $validatedData['kode'] = $kode;
                $validatedData['angka_kredit'] = $request->input('angka_kredit');


            }
        
        // 5 Menjadi Anggota Delegasi Nasional Ke Pertemuan Internasional
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Menjadi Anggota Delegasi Nasional Ke Pertemuan Internasional') {
                        // deklarasi variabel
                        $ketua = 'Sebagai Ketua Delegasi, Tiap Kegiatan';
                        $anggota = 'Sebagai Anggota, Tiap Kegiatan';
                        $kode_ketua = '8.5.a';
                        $kode_anggota = '8.5.b';

                        #Ketua
                        if($request->input('komponen_kegiatan') == $ketua){
                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                            //                                                     ->where('kode',$kode_ketua)->sum('angka_kredit');

                            //     $result = $count_angka_kredit + $request->input('angka_kredit');

                            //     // cek maksimal angka kredit
                            //         if($result > 3){
                            //             $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                            //             Alert::error($errorMessage);
                            //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            //         }

                                $validatedData['kode'] = $kode_ketua;
                                $validatedData['angka_kredit'] = $request->input('angka_kredit');
                                $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                        
                        }
                        # Anggota
                        elseif($request->input('komponen_kegiatan') == $anggota){

                                // // validasi maksimal angka kredit 
                                //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                                //                                                     ->where('kode',$kode_anggota)->sum('angka_kredit');

                                // $result = $count_angka_kredit + $request->input('angka_kredit');

                                // // cek maksimal angka kredit
                                // if($result > 2){
                                //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                                //     Alert::error($errorMessage);
                                //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                // }

                                $validatedData['kode'] = $kode_anggota;
                                $validatedData['angka_kredit'] = $request->input('angka_kredit');
                                $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                            
                        }
                }
            

        // 6 Berperan Serta Aktif Dalam Pengelolaan Jurnal Ilmiah (Per Tahun)
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Berperan Serta Aktif Dalam Pengelolaan Jurnal Ilmiah (Per Tahun)') {
                    // deklarasi variabel
                    $editor_int = 'Editor/ Dewan Penyunting/ Dewan Redaksi Jurnal Ilmiah Internasional';
                    $editor_nas = 'Editor/ Dewan Penyunting/ Dewan Redaksi Jurnal Ilmiah Nasional';
                    $kode_editor_int = '8.6.a';
                    $kode_editor_nas = '8.6.b';

                    #Editor Internasional
                    if($request->input('komponen_kegiatan') == $editor_int){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_editor_int)->sum('angka_kredit');

                        //     $result = $count_angka_kredit + $request->input('angka_kredit');

                        //     // cek maksimal angka kredit
                        //         if($result > 4){
                        //             $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 4. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //             Alert::error($errorMessage);
                        //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //         }

                            $validatedData['kode'] = $kode_editor_int;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                    }
                    # editor_nas
                    elseif($request->input('komponen_kegiatan') == $editor_nas){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                            //                                                     ->where('kode',$kode_editor_nas)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 2){
                            //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $kode_editor_nas;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');

                        
                    }
            }

        // 7 a Berperan Serta Aktif Dalam Pertemuan Ilmiah
            // Tingkat Internasional/ Nasional/ Regional Sebagai
            if ($request->filled('tingkat_int_nas_reg_sebagai') && $request->input('komponen_kegiatan') == 'Tingkat Internasional/ Nasional/ Regional Sebagai'){
                // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
            
                // declare the variable
                $ketua = '8.7.a.1';
                $anggota = '8.7.a.2';

                # Ketua
                if($request->input('tingkat_int_nas_reg_sebagai') == $ketua){
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$ketua)->sum('angka_kredit');
                    
                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     if($result > 3){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $ketua;
                    $validatedData['nilai_kegiatan'] = 'Ketua, Tiap Kegiatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                
                # Anggota
                }elseif($request->input('tingkat_int_nas_reg_sebagai') == $anggota){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 2){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota;
                    $validatedData['nilai_kegiatan'] = 'Anggota/ Peserta, Tiap Kepanitiaan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    

                }

            }elseif($request->input('komponen_kegiatan') == 'Tingkat Internasional/ Nasional/ Regional Sebagai'){
                $validator = Validator::make($request->all(), [
                    'tingkat_int_nas_reg_sebagai' => 'max:255|required',
                ],[
                    'tingkat_int_nas_reg_sebagai.required' => 'Error',
                    'tingkat_int_nas_reg_sebagai.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {
                    // Alert::error($validator->errors()->first());
                    // return redirect()->back()->withInput()->withErrors($validator);

                    $errorMessage = 'Nilai dari Kegiatan Berperan Serta Aktif Dalam Pertemuan Ilmiah Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }


            }


        // 7 b Berperan Serta Aktif Dalam Pertemuan Ilmiah
            // Di Lingkungan Perguruan Tinggi Sebagai
            if ($request->filled('ling_perguruan_tinggi_sebagai') && $request->input('komponen_kegiatan') == 'Di Lingkungan Perguruan Tinggi Sebagai'){
                // kalo kode ada isi dengan value komponen kegiatan sesuai dengan kondisi jalankan ini
            
                // declare the variable
                $ketua = '8.7.b.1';
                $anggota = '8.7.b.2';

                # Ketua
                if($request->input('ling_perguruan_tinggi_sebagai') == $ketua){
                    // // validasi maksimal angka kredit 
                    // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$ketua)->sum('angka_kredit');
                    
                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     if($result > 2){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $ketua;
                    $validatedData['nilai_kegiatan'] = 'Ketua, Tiap Kegiatan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    
                
                # Anggota
                }elseif($request->input('ling_perguruan_tinggi_sebagai') == $anggota){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                 ->where('kode',$anggota)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');
        

                    //     if($result > 1){
                    //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //         Alert::error($errorMessage);
                    //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //     }

                    $validatedData['kode'] = $anggota;
                    $validatedData['nilai_kegiatan'] = 'Anggota/ Peserta, Tiap Kepanitiaan';
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    

                }

            }elseif($request->input('komponen_kegiatan') == 'Di Lingkungan Perguruan Tinggi Sebagai'){
                $validator = Validator::make($request->all(), [
                    'ling_perguruan_tinggi_sebagai' => 'max:255|required',
                ],[
                    'ling_perguruan_tinggi_sebagai.required' => 'Error',
                    'ling_perguruan_tinggi_sebagai.max' => 'Tidak boleh lebih dari 255 karakter'
                ]);

                // error
                if ($validator->fails()) {
                    // Alert::error($validator->errors()->first());
                    // return redirect()->back()->withInput()->withErrors($validator);

                    $errorMessage = 'Nilai dari Kegiatan Berperan Serta Aktif Dalam Pertemuan Ilmiah Harus Diisi';
                    $validator->errors()->add('buat_error', $errorMessage);
                    
                    Alert::error($validator->errors()->first());
                    return redirect()->back()->withInput()->withErrors($validator);
                }


            }
        
        // 8 Mendapat Tanda Jasa/ Penghargaan
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Mendapat Tanda Jasa/ Penghargaan') {
                    // deklarasi variabel
                    $a8 = 'Penghargaan/ Tanda Jasa Satya Lencana 30 Tahun';
                    $b8 = 'Penghargaan/ Tanda Jasa Satya Lencana 20 Tahun';
                    $c8 = 'Penghargaan/ Tanda Jasa Satya Lencana 10 Tahun';
                    $d8 = 'Tingkat Internasional, Tiap Tanda Jasa/ Penghargaan';
                    $e8 = 'Tingkat Nasional, Tiap Tanda Jasa/ Penghargaan';
                    $f8 = 'Tingkat Daerah/ Lokal, Tiap Tanda Jasa/ Penghargaan';
                    $kode_a8 = '8.8.a';
                    $kode_b8 = '8.8.b';
                    $kode_c8 = '8.8.c';
                    $kode_d8 = '8.8.d';
                    $kode_e8 = '8.8.e';
                    $kode_f8 = '8.8.f';

                    # a Penghargaan/ Tanda Jasa Satya Lencana 30 Tahun
                    if($request->input('komponen_kegiatan') == $a8){
                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_a8)->sum('angka_kredit');

                        //     $result = $count_angka_kredit + $request->input('angka_kredit');

                        //     // cek maksimal angka kredit
                        //         if($result > 3){
                        //             $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //             Alert::error($errorMessage);
                        //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        //         }

                            $validatedData['kode'] = $kode_a8;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }

                    # b Penghargaan/ Tanda Jasa Satya Lencana 20 Tahun
                    elseif($request->input('komponen_kegiatan') == $b8){

                            // // validasi maksimal angka kredit 
                            //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                            //                                                     ->where('kode',$kode_b8)->sum('angka_kredit');

                            // $result = $count_angka_kredit + $request->input('angka_kredit');

                            // // cek maksimal angka kredit
                            // if($result > 2){
                            //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 2. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                            //     Alert::error($errorMessage);
                            //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                            // }

                            $validatedData['kode'] = $kode_b8;
                            $validatedData['angka_kredit'] = $request->input('angka_kredit');
                            $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }

                    # c Penghargaan/ Tanda Jasa Satya Lencana 10 Tahun
                    elseif($request->input('komponen_kegiatan') == $c8){

                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_c8)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // // cek maksimal angka kredit
                        // if($result > 1){
                        //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        $validatedData['kode'] = $kode_c8;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }

                    # d Tingkat Internasional, Tiap Tanda Jasa/ Penghargaan
                    elseif($request->input('komponen_kegiatan') == $d8){

                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_d8)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // // cek maksimal angka kredit
                        // if($result > 1){
                        //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        $validatedData['kode'] = $kode_d8;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }

                    # e Tingkat Nasional, Tiap Tanda Jasa/ Penghargaan
                    elseif($request->input('komponen_kegiatan') == $e8){

                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_e8)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // // cek maksimal angka kredit
                        // if($result > 3){
                        //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        $validatedData['kode'] = $kode_e8;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }

                    # f Tingkat Daerah/ Lokal, Tiap Tanda Jasa/ Penghargaan
                    elseif($request->input('komponen_kegiatan') == $f8){

                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_f8)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // // cek maksimal angka kredit
                        // if($result > 1){
                        //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        $validatedData['kode'] = $kode_f8;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                    }

            }
        
        // 9 Menulis Buku Pelajaran SLTA Ke Bawah Yang Diterbitkan Dan Diedarkan Secara Nasional
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Menulis Buku Pelajaran SLTA Ke Bawah Yang Diterbitkan Dan Diedarkan Secara Nasional') {
                // deklarasi variabel
                $a9 = 'Buku SMTA Atau Setingkat, Tiap Buku';
                $b9 = 'Buku SMTP Atau Setingkat, Tiap Buku';
                $c9 = 'Buku SD Atau Setingkat, Tiap Buku';
                $kode_a9 = '8.9.a';
                $kode_b9 = '8.9.b';
                $kode_c9 = '8.9.c';

                # a Buku SMTA Atau Setingkat, Tiap Buku
                if($request->input('komponen_kegiatan') == $a9){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                     ->where('kode',$kode_a9)->sum('angka_kredit');

                    //     $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     // cek maksimal angka kredit
                    //         if($result > 5){
                    //             $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //             Alert::error($errorMessage);
                    //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //         }

                        $validatedData['kode'] = $kode_a9;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }

                # b Buku SMTP Atau Setingkat, Tiap Buku
                elseif($request->input('komponen_kegiatan') == $b9){

                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_b9)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // // cek maksimal angka kredit
                        // if($result > 5){
                        //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        $validatedData['kode'] = $kode_b9;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }

                # c Buku SD Atau Setingkat, Tiap Buku
                elseif($request->input('komponen_kegiatan') == $c9){

                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                     ->where('kode',$kode_c9)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // // cek maksimal angka kredit
                    // if($result > 5){
                    //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    $validatedData['kode'] = $kode_c9;
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }


            }
        
        // 10 Mempunyai Prestasi Di Bidang Olahraga/ Humaniora
            if ($request->filled('tipe_kegiatan') && $request->filled('komponen_kegiatan') &&
                $request->input('tipe_kegiatan') == 'Mempunyai Prestasi Di Bidang Olahraga/ Humaniora') {
                
                    // deklarasi variabel
                $a10 = 'Tingkat Internasional, Tiap Piagam/ Medali';
                $b10 = 'Tingkat Nasional, Tiap Piagam/ Medali';
                $c10 = 'Tingkat Daerah/Lokal, Tiap Piagam/ Medali';
                $kode_a10 = '8.10.a';
                $kode_b10 = '8.10.b';
                $kode_c10 = '8.10.c';

                # a Tingkat Internasional, Tiap Piagam/ Medali
                if($request->input('komponen_kegiatan') == $a10){
                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                     ->where('kode',$kode_a10)->sum('angka_kredit');

                    //     $result = $count_angka_kredit + $request->input('angka_kredit');

                    //     // cek maksimal angka kredit
                    //         if($result > 5){
                    //             $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 5. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //             Alert::error($errorMessage);
                    //             return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    //         }

                        $validatedData['kode'] = $kode_a10;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }

                # b Tingkat Nasional, Tiap Piagam/ Medali
                elseif($request->input('komponen_kegiatan') == $b10){

                        // // validasi maksimal angka kredit 
                        //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                        //                                                     ->where('kode',$kode_b10)->sum('angka_kredit');

                        // $result = $count_angka_kredit + $request->input('angka_kredit');

                        // // cek maksimal angka kredit
                        // if($result > 3){
                        //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 3. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                        //     Alert::error($errorMessage);
                        //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                        // }

                        $validatedData['kode'] = $kode_b10;
                        $validatedData['angka_kredit'] = $request->input('angka_kredit');
                        $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }

                # c Tingkat Daerah/Lokal, Tiap Piagam/ Medali
                elseif($request->input('komponen_kegiatan') == $c10){

                    // // validasi maksimal angka kredit 
                    //     $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                    //                                                     ->where('kode',$kode_c10)->sum('angka_kredit');

                    // $result = $count_angka_kredit + $request->input('angka_kredit');

                    // // cek maksimal angka kredit
                    // if($result > 1){
                    //     $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                    //     Alert::error($errorMessage);
                    //     return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                    // }

                    $validatedData['kode'] = $kode_c10;
                    $validatedData['angka_kredit'] = $request->input('angka_kredit');
                    $validatedData['komponen_kegiatan'] = $request->input('komponen_kegiatan');
                }


            }

        // 11 Keanggotaan Dalam Tim Penilai Jabatan Akademik Dosen (Tiap Semester)
            if($request->input('tipe_kegiatan') == 'Keanggotaan Dalam Tim Penilai Jabatan Akademik Dosen (Tiap Semester)'){
                // deklarasi variable
                $kode = '8.11';

                // $count_angka_kredit = pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->where('kategori_pak_id',4)
                //                                                                 ->where('kode',$kode)->sum('angka_kredit');

                // $result = $count_angka_kredit + $request->input('angka_kredit');

                //     if($result > 1){
                //         $errorMessage = 'Angka Kredit Paling Tinggi Yaitu 1. Angka Kredit Anda Sekarang adalah '. $count_angka_kredit;
                //         Alert::error($errorMessage);
                //         return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                //     }


                $validatedData['kode'] = $kode;
                $validatedData['angka_kredit'] = $request->input('angka_kredit');


            }

        // Handle Tanggal Pelaksanaan
            // $validatedData['tanggal_pelaksanaan'] = Carbon::createFromFormat('Y-m-d', $request->input('tanggal_pelaksanaan'))->format('d-m-Y');
            // $validatedData['tanggal_pelaksanaan'] = Carbon::createFromFormat('d-m-Y', $request->input('tanggal_pelaksanaan'))->format('Y-m-d');
            // $validatedData['tanggal_pelaksanaan'] = Carbon::createFromFormat('d-m-Y', $request->input('tanggal_pelaksanaan'))->toDateString();



        // ubah nama slug 
            $slug = Str::slug($request->input('kegiatan'));
            $random_token = Str::random(5);
            $slug_name = $slug.'-'. $random_token;
  
        // Handle upload bukti pdf
            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));

        $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/'. $direktori_dosen, time().'-'.$nama_bukti.'.pdf');
  
        $validatedData['slug'] = $slug_name;
        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['kategori_pak_id'] = 4;
  
        // Create 
            pak_kegiatan_penunjang_tri_dharma_pt::create($validatedData);
        
            Alert::success('Berhasil','Menambahkan Kegiatan');
            return redirect()->route('penunjang-tri-dharma-pt');


    }

    public function penunjang_tri_dharma_pt_destroy($slug){
        // Retrieve the record from the table based on the slug
        $record = pak_kegiatan_penunjang_tri_dharma_pt::where('slug', $slug)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        if($record->bukti){
            Storage::delete($record->bukti);
        }
        

        pak_kegiatan_penunjang_tri_dharma_pt::destroy($record->id);

        Alert::success('Sukses','File Berhasil dihapus');
        return redirect()->route('penunjang-tri-dharma-pt');
    }

    public function penunjang_tri_dharma_pt_detail($detail){
        
        // Retrieve the record from the table based on the slug
        $record = pak_kegiatan_penunjang_tri_dharma_pt::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        return view('dosen.simulasi.penunjang_tri_dharma_pt.detail',[
            'title' => 'Penunjang Tri Dharma PT',
            'record' => $record,
        ]);

    }

   
    public function  penunjang_tri_dharma_pt_edit_store($detail, Request $request){
        // Retrieve the record from the table based on the slug
            $record = pak_kegiatan_penunjang_tri_dharma_pt::where('slug', $detail)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }


        $validator = Validator::make($request->all(),[
            'kegiatan' => 'max:255',
            'bukti' => 'mimes:pdf|max:1024|',
            'angka_kredit' => 'max:255',
            'tempat' => 'max:255',
            'tanggal_pelaksanaan' => 'date'
        ],[
            'kegiatan.max' => 'Maksimal 255 Karakter',
            'kegiatan.regex' => 'Nama Kegiatan hanya boleh mengandung huruf dan spasi',
            'bukti.max' => 'Maksimal file 1 MB',
            'bukti.mimes' => 'File harus format pdf',
        ]);

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Mengedit Data');
        }

        // simpan data
        $validatedData = $validator->validated();

        if ($request->input('kegiatan') == NULL || $request->input('angka_kredit') == NULL ||
            $request->input('tempat') == NULL || $request->input('tanggal_pelaksanaan') == NULL
        ){
            $validator = Validator::make($request->all(),[
                'kegiatan' => 'required',
                'angka_kredit' => 'required',
                'tempat' => 'required',
                'tanggal_pelaksanaan' => 'required',
            ],[
                'kegiatan.required' => 'Kegiatan harus diisi',
                'angka_kredit.required' => 'Angka Kredit harus diisi',
                'tempat.required' => 'Tempat harus diisi',
                'tanggal_pelaksanaan.required' => 'Tanggal Pelaksanaan harus diisi',
            ]);

            if ($validator->fails()) {
                Alert::error($validator->errors()->all()[0]);
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }


        if (!$request->hasFile('bukti')) {
            // Handle the case where the user didn't provide a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            // Use Storage::copy to copy the existing file to the desired directory
            $newFilePath = 'dosen/' . $direktori_dosen . '/' . time() . '-' . $nama_bukti . '.pdf';
            Storage::copy($record->bukti, $newFilePath);
        
            $validatedData['bukti'] = $newFilePath;
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        } else {
            // Handle the case where the user provided a file

            $namadosen = auth()->user()->name;
            $direktori_dosen = Str::slug($namadosen);
            $nama_bukti = Str::slug($request->input('kegiatan'));
        
            $validatedData['bukti'] = $request->file('bukti')->storeAs('dosen/' . $direktori_dosen, time() . '-' . $nama_bukti . '.pdf');
        
            // Delete the old file
            if ($record->bukti) {
                Storage::delete($record->bukti);
            }
        }
        
    
        // Create 
            pak_kegiatan_penunjang_tri_dharma_pt::where('slug', $detail)->update($validatedData);

            Alert::success('Berhasil','Update Data');
            return redirect()->back();

    }

}
