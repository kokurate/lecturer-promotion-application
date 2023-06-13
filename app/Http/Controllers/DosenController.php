<?php

namespace App\Http\Controllers;

use App\Models\my_storage;
use App\Models\berkas_kenaikan_pangkat_reguler;
use App\Models\history;
use App\Models\status_kenaikan_pangkat;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


use Illuminate\Http\Request;
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
        return view('dosen.simulasi.index',[
            'title' => 'Dosen | Simulasi',

        ]);

    }

    public function pendidikan_dan_pengajaran(){
        return view('dosen.simulasi.pendidikan_dan_pengajaran',[
        'title' => 'Simulasi Pendidikan dan Pengajaran',
    ]);
    }


}
