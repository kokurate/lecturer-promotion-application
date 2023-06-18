<?php

namespace App\Http\Controllers;

use App\Models\my_storage;
use App\Models\berkas_kenaikan_pangkat_reguler;
use App\Models\history;
use App\Models\kategori_pak;
use App\Models\pak_kegiatan_pendidikan_dan_pengajaran;
use App\Models\status_kenaikan_pangkat;
use App\Models\tahun_ajaran;
use App\Models\tipe_kegiatan_pendidikan_dan_pengajaran;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


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

        return view('dosen.simulasi.index',[
            'title' => 'Dosen | Simulasi',
            // 'kategori_pak' => kategori_pak::all(),
            'kategori_pak' => kategori_pak::withCount(['pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }])
                ->with(['pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }])
                ->get(),
            'total_kegiatan' => pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->count('id'),
            'jumlah_kredit' => pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->sum('angka_kredit'),
                                                                        
        ]);

    }

 



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
            'kegiatan' => 'regex:/^[a-zA-Z\s]+$/|required|max:255|',
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

                    //    // Cek apakah pengguna telah memilih jenis pendidikan formal sebelumnya
                    //     $previousPendidikan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                    //     ->where('jenis_pendidikan', $jenisPendidikan)
                    //     ->first();

                    //     // jika sudah pernah kasih error
                    //     if ($previousPendidikan) {
                    //         Alert::error('Anda sudah memasukkan pendidikan ' . $jenisPendidikan);
                    //         return redirect()->back()->withInput()->withErrors(['jenis_pendidikan' => 'Anda sudah memasukkan pendidikan ' . $jenisPendidikan])
                    //                 ->with('error', 'Gagal Menambahkan Kegiatan');
                    //     }

                        // Mengecek apakah pengguna sudah memasukkan jenis pendidikan doktor atau magister sebelumnya
                        $existingPendidikan = pak_kegiatan_pendidikan_dan_pengajaran::where(function ($query) use ($jenisPendidikan, $user_id) {
                            $query->where('jenis_pendidikan', 'doktor')
                                ->orWhere('jenis_pendidikan', 'magister');
                        })
                            ->where('user_id', $user_id)->where('kategori_pak_id', 1)
                            ->exists();

                        if ($existingPendidikan) {
                            // Menampilkan pesan error jika pengguna sudah memasukkan jenis pendidikan doktor atau magister sebelumnya
                            Alert::error('Anda sudah memasukkan jenis pendidikan doktor atau magister sebelumnya');
                            return redirect()->back()->withInput()->withErrors(['jenis_pendidikan' => 'Anda sudah memasukkan jenis pendidikan doktor atau magister sebelumnya'])->with('error', 'Gagal Menambahkan Kegiatan');
                        }

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
                    $existingDiklat = pak_kegiatan_pendidikan_dan_pengajaran::where('tipe_kegiatan', $request->input('tipe_kegiatan'))
                        ->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                        ->exists();

                    if ($existingDiklat) {
                        // Menampilkan pesan error jika pengguna sudah memasukkan Diklat pra jabatan sebelumnya
                        Alert::error('Anda sudah memasukkan Diklat Pra-Jabatan sebelumnya');
                        return redirect()->back()->withInput()->withErrors(['tipe_kegiatan' => 'Anda sudah memasukkan Diklat Pra-Jabatan sebelumnya'])->with('error', 'Gagal Menambahkan Kegiatan');
                    }

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
                            $sksNow = $request->sks_now;

                            // Mendapatkan jumlah SKS yang dimasukkan melalui request
                            $perkuliahan = $request->perkuliahan;

                            // Validasi jumlah SKS
                            $totalSks = $sksNow + $perkuliahan;

                             if ($totalSks <= 10) {
                                // Hitung jumlah SKS
                                $nilaiSks = $perkuliahan * 0.5;
                                $validatedData['kode'] = 'II.A.1.a';
                            }elseif($totalSks > 10 && $totalSks <= 12){
                                // Hitung jumlah SKS
                                $sks10 = 10 - $sksNow; // SKS yang dibutuhkan untuk mencapai 10
                                $sksLain = $perkuliahan - $sks10; // Sisa SKS di atas 10
                                $nilaiSks = ($sks10 * 0.5) + ($sksLain * 0.25);
                                $validatedData['kode'] = 'II.A.1.b';
                            }elseif($totalSks > 11 && $totalSks = 12){


                                $nilaiSks = $perkuliahan * 0.25;
                                $validatedData['kode'] = 'II.A.1.a';

                            }
                            else {   
                                Alert::error('Jumlah SKS sudah melebihi batas maksimal.');
                                return redirect()->back()->withInput()->withErrors(['total_sks' => 'Total SKS melebihi batas maksimum (12 SKS).']);
                            }
                           

                            // if ($totalSks <= 10) {
                            //     // Hitung jumlah SKS
                            //     $nilaiSks = $totalSks * 0.5;
                            //     $validatedData['kode'] = 'II.A.1.a';
                            // } else {
                            //     // Hitung jumlah SKS
                            //     $nilaiSks = (10 * 0.5) + (($totalSks - 10) * 0.25);
                            //     $validatedData['kode'] = 'II.A.1.b';
                            // }
                            
                            // if ($totalSks > 12) {
                            //     // Tampilkan pesan error jika total SKS melebihi 12
                            //     Alert::error('Jumlah SKS sudah melebihi batas maksimal.');
                            //     return redirect()->back()->withInput()->withErrors(['total_sks' => 'Total SKS melebihi batas maksimum (12 SKS).']);
                            // }


                            // Operasi lama 
                            // // Mengatur nilai SKS berdasarkan kondisi
                            // if ($totalSks <= 10) {
                            //     $nilaiSks = ($perkuliahan * 0.5);
                            //     $validatedData['kode'] = 'II.A.1.a';
                            // } elseif ($totalSks > 10 && $totalSks <= 11) {
                            //     $nilaiSks = ($perkuliahan * 0.25);
                            //     $validatedData['kode'] = 'II.A.1.b';
                            // } elseif ($totalSks > 11 && $totalSks <= 12) {
                            //     $nilaiSks =  ($perkuliahan * 0.25);
                            //     $validatedData['kode'] = 'II.A.1.b';
                            // } else {
                            //     // Pesan error jika jumlah SKS melebihi batas maksimal
                            //     Alert::error('Jumlah SKS sudah melebihi batas maksimal.');
                            //     return redirect()->back()->withErrors(['perkuliahan' => 'Jumlah SKS sudah melebihi batas maksimal.'])->withInput();
                            // }

                            // Set nilai angka kredit sesuai dengan nilai SKS
                            $validatedData['angka_kredit'] = $nilaiSks;
                            $validatedData['sks'] = $perkuliahan;
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
                            $sksNow = $request->sks_now;

                            // Mendapatkan jumlah SKS yang dimasukkan melalui request
                            $perkuliahan = $request->perkuliahan;

                            // Validasi jumlah SKS
                            $totalSks = $sksNow + $perkuliahan;


                            if ($totalSks <= 10) {
                                // Hitung jumlah SKS
                                $nilaiSks = $perkuliahan * 1;
                                $validatedData['kode'] = 'II.A.2.b';
                            }elseif($totalSks > 10 && $totalSks <= 12){
                                // Hitung jumlah SKS
                                $sks10 = 10 - $sksNow; // SKS yang dibutuhkan untuk mencapai 10
                                $sksLain = $perkuliahan - $sks10; // Sisa SKS di atas 10
                                $nilaiSks = ($sks10 * 1) + ($sksLain * 0.5);
                                $validatedData['kode'] = 'II.A.2.b';
                            }elseif($totalSks > 11 && $totalSks = 12){


                                $nilaiSks = $perkuliahan * 0.5;
                                $validatedData['kode'] = 'II.A.2.b';

                            }
                            else {   
                                Alert::error('Jumlah SKS sudah melebihi batas maksimal.');
                                return redirect()->back()->withInput()->withErrors(['total_sks' => 'Total SKS melebihi batas maksimum (12 SKS).']);
                            }


                            // // Mengatur nilai SKS berdasarkan kondisi
                            // if ($totalSks <= 10) {
                            //     $nilaiSks = ($perkuliahan * 1);
                            //     $validatedData['kode'] = 'II.A.2.a';
                            // } elseif ($totalSks > 10 && $totalSks <= 11) {
                            //     $nilaiSks = ($perkuliahan * 0.5);
                            //     $validatedData['kode'] = 'II.A.2.b';
                            // } elseif ($totalSks > 11 && $totalSks <= 12) {
                            //     $nilaiSks =  ($perkuliahan * 0.5);
                            //     $validatedData['kode'] = 'II.A.2.b';
                            // } else {
                            //     // Pesan error jika jumlah SKS melebihi batas maksimal
                            //     Alert::error('Jumlah SKS sudah melebihi batas maksimal.');
                            //     return redirect()->back()->withErrors(['perkuliahan' => 'Jumlah SKS sudah melebihi batas maksimal.'])->withInput();
                            // }

                            // Set nilai angka kredit sesuai dengan nilai SKS
                            $validatedData['angka_kredit'] = $nilaiSks;
                            $validatedData['sks'] = $perkuliahan;
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

                            // cek nilai angka kredit sekarang jika 11 maka error
                                if ($akNow == 11) {
                                    Alert::error('Total Angka Kredit melebihi batas maksimum.');
                                    return redirect()->back()->withInput()->withErrors(['dokter_klinis' => 'Total Angka Kredit melebihi batas maksimum.']);
                                } else {
                                    // tambahkan angka kredit ke nilai sekarang
                                    $rumus1 = $akNow + $angka_kredit;
                                    
                                    // cek jika total angka kredit melebihi 11
                                    if ($rumus1 > 11) {
                                        $rumus2 = $rumus1 - 11;
                                        $total_angka_kredit = $rumus1 - $rumus2;

                                        $validatedData['angka_kredit'] = $rumus2;
                                        $validatedData['kode'] = $request->input('dokter_klinis');
                                    } else {
                                        $total_angka_kredit = $rumus1;

                                        $validatedData['angka_kredit'] = $angka_kredit;
                                        $validatedData['kode'] = $request->input('dokter_klinis');
                                    }
                                    # Gunakan $total_angka_kredit di bagian lain dari kode jika diperlukan

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
                        $max_lulusan = 4;
                        $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                ->where('kode','II.D.1.a')->count();
                        // cek maksimal lulusan
                            if($count_lulusan >= $max_lulusan){
                                Alert::error('Jumlah lulusan sebagai Pembimbing Utama Disertasi melebihi batas maksimum');
                                return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Disertasi melebihi batas maksimum']);
                            }else{
                                $validatedData['angka_kredit'] = 8;
                                $validatedData['kode'] = $kode;
                                $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Disertasi';
                            }

                // cek kode Tesis
                    }elseif($kode == $p1_tesis){
                        $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                ->where('kode','II.D.1.b')->count();

                            // cek maksimal lulusan
                            if($count_lulusan >= 6){
                                Alert::error('Jumlah lulusan sebagai Pembimbing Utama Tesis melebihi batas maksimum');
                                return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Tesis melebihi batas maksimum']);
                            }else{
                                $validatedData['angka_kredit'] = 3;
                                $validatedData['kode'] = $kode;
                                $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Tesis';
                            }

                // cek kode Skripsi
                    }elseif($kode == $p1_skripsi){
                        $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                ->where('kode','II.D.1.c')->count();

                            // cek maksimal lulusan
                            if($count_lulusan >= 8){
                                Alert::error('Jumlah lulusan sebagai Pembimbing Utama Skripsi melebihi batas maksimum');
                                return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Skripsi melebihi batas maksimum']);
                            }else{
                                $validatedData['angka_kredit'] = 1;
                                $validatedData['kode'] = $kode;
                                $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Skripsi';
                            }

                // cek kode Laporan Akhir Studi
                    }elseif($kode == $p1_laporan_akhir_studi){
                        $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                ->where('kode','II.D.1.d')->count();

                            // cek maksimal lulusan
                            if($count_lulusan >= 10){
                                Alert::error('Jumlah lulusan sebagai Pembimbing Utama Laporan Akhir Studi melebihi batas maksimum');
                                return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Utama Laporan Akhir Studi melebihi batas maksimum']);
                            }else{
                                $validatedData['angka_kredit'] = 1;
                                $validatedData['kode'] = $kode;
                                $validatedData['komponen_kegiatan'] = 'Pembimbing Utama Laporan Akhir Studi';
                            }
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
                            $max_lulusan = 4;
                            $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                    ->where('kode','II.D.2.a')->count();
                            // cek maksimal lulusan
                                if($count_lulusan >= $max_lulusan){
                                    Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Disertasi melebihi batas maksimum');
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Disertasi melebihi batas maksimum']);
                                }else{
                                    $validatedData['angka_kredit'] = 6;
                                    $validatedData['kode'] = $kode;
                                    $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Disertasi';

                                }

                    // cek kode Tesis
                        }elseif($kode == $p2_tesis){
                            $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                    ->where('kode','II.D.2.b')->count();
                            // cek maksimal lulusan
                                if($count_lulusan >= 6){
                                    Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Tesis melebihi batas maksimum');
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Tesis melebihi batas maksimum']);
                                }else{
                                    $validatedData['angka_kredit'] = 2;
                                    $validatedData['kode'] = $kode;
                                    $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Tesis';
                                }
                        }
                    // cek kode skripsi
                        elseif($kode == $p2_skripsi){
                            $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                    ->where('kode','II.D.2.c')->count();
                            // cek maksimal lulusan
                                if($count_lulusan >= 8){
                                    Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Skripsi melebihi batas maksimum');
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Skripsi melebihi batas maksimum']);
                                }else{
                                    $validatedData['angka_kredit'] = 0.5;
                                    $validatedData['kode'] = $kode;
                                    $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Skripsi';
                                }
                        }
                    // cek kode Laporan Akhir Studi
                        elseif($kode == $p2_laporan_akhir_studi){
                            $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                    ->where('kode','II.D.2.d')->count();
                            // cek maksimal lulusan
                                if($count_lulusan >= 10){
                                    Alert::error('Jumlah lulusan sebagai Pembimbing Pendamping Laporan Akhir Studi melebihi batas maksimum');
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Pembimbing Pendamping Laporan Akhir Studi melebihi batas maksimum']);
                                }else{
                                    $validatedData['angka_kredit'] = 0.5;
                                    $validatedData['kode'] = $kode;
                                    $validatedData['komponen_kegiatan'] = 'Pembimbing Pendamping Laporan Akhir Studi';
                                }
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

                                $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                        ->where('kode','II.E.1')->count();
                                // cek maksimal lulusan
                                    if($count_lulusan >= 4){
                                        Alert::error('Jumlah lulusan sebagai Ketua Penguji sudah melebihi batas maksimum');
                                        return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Ketua Penguji sudah melebihi batas maksimum']);
                                    }else{
                                        $validatedData['angka_kredit'] = 1;
                                        $validatedData['kode'] = $kode;
                                        $validatedData['komponen_kegiatan'] = 'Ketua Penguji';
                                    }

                        }elseif($kode == $anggota_penguji){
                            // 
                                $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                        ->where('kode','II.E.2')->count();
                                // cek maksimal lulusan
                                    if($count_lulusan >= 8){
                                        Alert::error('Jumlah lulusan sebagai Anggota Penguji sudah melebihi batas maksimum');
                                        return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah lulusan sebagai Anggota Penguji sudah melebihi batas maksimum']);
                                    }else{
                                        $validatedData['angka_kredit'] = 0.5;
                                        $validatedData['kode'] = $kode;
                                        $validatedData['komponen_kegiatan'] = 'Anggota Penguji';
                                    }
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
                    
                    $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                            ->where('kode','II.F')->count();
                    // cek maksimal kegiatan
                        if($count_lulusan >= 2){
                            Alert::error('Kegiatan Membina kegiatan mahasiswa di bidang akademik dan kemahasiswan sudah maksimum');
                            return redirect()->back()->withInput()->withErrors(['buat_error' => 'Kegiatan Membina kegiatan mahasiswa di bidang akademik dan kemahasiswan sudah maksimum']);
                        }else{
                            $validatedData['angka_kredit'] = 2;
                            $validatedData['kode'] = 'II.F';
                        }               
                }

        
        // ========================== 9
            // Mengembangkan Program Kuliah
            if($request->input('tipe_kegiatan') == 'Mengembangkan Program Kuliah'){
                
                $count_lulusan = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                        ->where('kode','II.G')->count();
                // cek maksimal mata kuliah/semester
                    if($count_lulusan >= 1){
                        Alert::error('Menggembangkan program kuliah sudah melebihi batas maksimum');
                        return redirect()->back()->withInput()->withErrors(['buat_error' => 'Menggembangkan program kuliah sudah melebihi batas maksimum']);
                    }else{
                        $validatedData['angka_kredit'] = 2;
                        $validatedData['kode'] = 'II.G';
                        $validatedData['komponen_kegiatan'] = 'Mengembangkan program kuliah yang mempunyai nilai kebaharuan metode atau subtansi (setiap produk)';
                    }               
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

                                    $count_produk = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                            ->where('kode', $buku)->count();
                                    // cek maksimal lulusan
                                        if($count_produk >= 1){
                                            Alert::error('Jumlah Buku Ajar maksimal 1');
                                            return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah Buku Ajar maksimal 1']);
                                        }else{
                                            $validatedData['angka_kredit'] = 20;
                                            $validatedData['kode'] = $kode;
                                            $validatedData['komponen_kegiatan'] = 'Buku Ajar';
                                        }
                            } // cek kondisi kalau diklat dll
                            elseif($kode == $diklat){

                                $count_produk = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                    ->where('kode', $diklat)->count();
                                // cek maksimal lulusan
                                    if($count_produk >= 1){
                                        Alert::error('Jumlah Diklat, Modul dsb maksimal 1');
                                        return redirect()->back()->withInput()->withErrors(['buat_error' => 'Jumlah Diklat, Modul dsb maksimal 1']);
                                    }else{
                                        $validatedData['angka_kredit'] = 5;
                                        $validatedData['kode'] = $kode;
                                        $validatedData['komponen_kegiatan'] = 'Diklat,Modul,Ptunjuk praktikum,Model,Alat bantu, Alat visual, 
                                                                                Naskah	tutorial, Job sheet praktikum terkait dengan 
                                                                                mata kuliah yang dilampau';
                                    }

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

                $count_orasi = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                    ->where('kode',$orasi)->count();
            
            // cek maksimal orasi
                if($count_orasi >= 2){
                    Alert::error('Maksimal 2 orasi');
                    return redirect()->back()->withInput()->withErrors(['buat_error' => 'Maksimal 2 orasi']);
                }else{
                    $validatedData['angka_kredit'] = 5;
                    $validatedData['kode'] = $orasi;
                }               
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
                        $count_rektor = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                            ->where('kode',$kode_1)->count();
                        $count_wakil_rektor = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                                                        ->where('kode',$kode_2)->count();

                    
                    // cek maksimal jabatan
                        if($count_rektor >= 1 || $count_wakil_rektor >= 1){
                            Alert::error('Hanya dapat memiliki maksimal 1 jabatan sebagai rektor ataupun wakil rektor.');
                            return redirect()->back()->withInput()->withErrors(['buat_error' => 'Hanya dapat memiliki maksimal 1 jabatan sebagai rektor ataupun wakil rektor.']);
                        }
                    
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
                            // ambil count maksimal
                                $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                                                                                        ->where('kategori_pak_id',1)
                                                                                        ->where('kode', $pembimbing_pencangkokan)->count();
                        
                            // cek maksimal 
                                if($count_maksimal >= 1){
                                    $errorMessage = 'Pembimbing Pencangkokan sudah maksimal';
                                    Alert::error($errorMessage);
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                }else{
                                    $validatedData['angka_kredit'] = 2;
                                    $validatedData['kode'] = $pembimbing_pencangkokan;
                                    $validatedData['komponen_kegiatan'] = 'Pembimbing Pencangkokan';
                                }

                        }
                        // pembimbing Reguler
                        elseif($kode_request == $reguler){
                            // ambil count maksimal
                                $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                                                                                        ->where('kategori_pak_id',1)
                                                                                        ->where('kode', $reguler)->count();
                        
                                // cek maksimal 
                                if($count_maksimal >= 1){
                                    $errorMessage = 'Pembimbing Reguler sudah maksimal';
                                    Alert::error($errorMessage);
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                }else{
                                    $validatedData['angka_kredit'] = 1;
                                    $validatedData['kode'] = $reguler;
                                    $validatedData['komponen_kegiatan'] = 'Pembimbing Reguler';
                                }
                        
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
                            // ambil count maksimal
                                $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                                                                                        ->where('kategori_pak_id',1)
                                                                                        ->where('kode', $detaseting)->count();
                        
                            // cek maksimal 
                                if($count_maksimal >= 1){
                                    $errorMessage = 'Kegiatan Detasering di luar institusi sudah maksimal';
                                    Alert::error($errorMessage);
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                }else{
                                    $validatedData['angka_kredit'] = 5;
                                    $validatedData['kode'] = $kode_request;
                                    $validatedData['komponen_kegiatan'] = 'Detasering';
                                }

                        }
                        // Pencangkokan
                        elseif($kode_request == $pencangkokan){
                            // ambil count maksimal
                                $count_maksimal = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)
                                                                                        ->where('kategori_pak_id',1)
                                                                                        ->where('kode', $pencangkokan)->count();
                        
                                // cek maksimal 
                                if($count_maksimal >= 1){
                                    $errorMessage = 'Kegiatan Pencangkokan di luar institusi sudah maksimal';
                                    Alert::error($errorMessage);
                                    return redirect()->back()->withInput()->withErrors(['buat_error' => $errorMessage]);
                                }else{
                                    $validatedData['angka_kredit'] = 4;
                                    $validatedData['kode'] = $kode_request;
                                    $validatedData['komponen_kegiatan'] = 'Pencangkokan';
                                }
                        
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
            'title' => 'Detail Pendidikan dan Pengajaran',
            'record' => $record,
        ]);

    }

    public function penelitian(){

    }

    public function pengabdian_pada_masyarakat(){

    }

    public function penunjang_tri_dharma_pt(){

    }


}
