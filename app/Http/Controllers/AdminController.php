<?php

namespace App\Http\Controllers;

use App\Models\fakultas;
use App\Models\kategori_pak;
use App\Models\tahun_ajaran;
use App\Models\tipe_kegiatan_pendidikan_dan_pengajaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index',[
            'title' => 'Admin Dashboard',
            'count_pegawai' => user::where('level','pegawai')->get()->count(), 
            'count_dosen' => user::where('level', 'dosen')->get()->count(),
        ]);
    }

    public function register_pegawai(){
        return view('admin.register_pegawai',[
            'title' => 'Registrasi',
            'fakultas' => fakultas::all(),
        ]);
    }

    public function register_pegawai_store(Request $request){
        $validator = Validator::make($request->all(),[ 
            'name' => 'required|max:255|string',
            'email' => 'required|email|unique:users,email',
            // 'jurusan_prodi' => 'required',
            'fakultas' => 'required|exists:fakultas,nama',
            'password' => 'required|min:8'
         ],[
            'fakultas.exists' => 'Belum Memilih Fakultas',
            'email.unique' => 'Email Sudah Terdaftar',
            'password.min' => 'Password minimal 8 karakter',
         ]);

         if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi
        $validatedData = $validator->validated();
        $validatedData['password'] = bcrypt($request->password); 
        $validatedData['level'] = 'pegawai'; 

        User::create($validatedData);
        // dd($validatedData);
        
        Alert::success('Registrasi Berhasil');
        return back();

    }

    public function simulasi(){
        return view('admin.simulasi.index',[
            'title' => 'Admin | Simulasi',
            'tahun_ajaran' => tahun_ajaran::orderBy('id', 'DESC')->get(),
            'kategori_simulasi' => kategori_pak::where('id','!=', false)->orderBy('id', 'DESC')->get(),
            'tipe_kegiatan_pendidikan_dan_pengajaran' => tipe_kegiatan_pendidikan_dan_pengajaran::where('id', '!=', false)->orderBy('id','DESC')->get()

        ]);
    }

    public function tambah_tahun_ajaran_store(Request $request){
        $validator = Validator::make($request->all(),[
            'tahun' => 'required|max:255|',
            'semester' => 'required|max:255',
        ],[
            'tahun.required' => 'Tahun harus diisi',
            'tahun.max' => 'Maksimal 255 Karakter',
            'semester.required' => 'Semester harus diisi',
            'semester.max' => 'Maksimal 255 karakter',
        ]);

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // simpan data
        $validatedData = $validator->validated();

         // Create 
         tahun_ajaran::create($validatedData);
    
         Alert::success('Berhasil','Menambahkan Tahun Ajaran');
         return redirect()->back();
    }
    public function tipe_kegiatan_pendidikan_dan_pengajaran_store(Request $request){
        
        $validator = Validator::make($request->all(),[
            'tipe_kegiatan_pendidikan_dan_pengajaran' => 'required|max:255|',
        ],[
            'tipe_kegiatan_pendidikan_dan_pengajaran.required' => 'Tipe Kegiatan Tahun harus diisi',
            'tipe_kegiatan_pendidikan_dan_pengajaran.max' => 'Maksimal 255 Karakter',
        ]);

        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // simpan data
        $validatedData = $validator->validated();

        $validatedData['nama_kegiatan'] = $request->input('tipe_kegiatan_pendidikan_dan_pengajaran');

        // dd($validatedData);
         // Create 
         tipe_kegiatan_pendidikan_dan_pengajaran::create($validatedData);
    
         Alert::success('Berhasil','Menambahkan Tipe Kegiatan');
         return redirect()->back();
    }

    public function kategori_simulasi_store(Request $request){
        $validator = Validator::make($request->all(),[
            'nama' => 'required|max:255|',
        ],[
            'nama.required' => 'Nama Kategori harus diisi',
            'nama.max' => 'Maksimal 255 Karakter',
        ]);
    
        // Error Message
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // simpan data
        $validatedData = $validator->validated();

        $slug = Str::slug($request->input('nama'));
        $namafileslug = Str::slug($request->input('nama'));

        $validatedData['slug'] = $namafileslug;

         // Create 
         kategori_pak::create($validatedData);
    
         Alert::success('Berhasil','Menambahkan Kategori Simulasi');
         return redirect()->back();
    }


    public function kategori_simulasi_destroy($id){

        // Retrieve the record from the table based on the id
        $record = kategori_pak::where('id', $id)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }
        kategori_pak::destroy($record->id);

        Alert::success('Sukses','Berhasil Hapus Data Kategori');
        return back();

    }

    public function tipe_kegiatan_pendidikan_dan_pengajaran_destroy($id){

        // Retrieve the record from the table based on the id
        $record = tipe_kegiatan_pendidikan_dan_pengajaran::where('id', $id)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }
        tipe_kegiatan_pendidikan_dan_pengajaran::destroy($record->id);

        Alert::success('Sukses','Berhasil Hapus Data Tipe Kegiatan');
        return back();

    }

    public function tambah_tahun_ajaran_destroy($id){

        // Retrieve the record from the table based on the id
        $record = tahun_ajaran::where('id', $id)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        tahun_ajaran::destroy($record->id);

        Alert::success('Sukses','Berhasil Hapus Data');
        return back();

    }

    public function tambah_tahun_ajaran_update($id){
        // Retrieve the record from the table based on the id
        $record = tahun_ajaran::where('id', $id)->first();

        if (!$record) {
            // Handle the case where the record is not found
            abort(404);
        }

        $now = true;
        $before = false;

        // Set all records' "now" value to false
            tahun_ajaran::where('id', '!=', $record->id)->update(['now' => false]);

        // Set the current record's "now" value to true
            $record->update(['now' => true]);


        Alert::success('Sukses','Berhasil Update Data');
        return back();

    }

    public function truncate_tahun_ajaran(){


            // Dapatkan data TahunAjaran yang memenuhi kondisi now = false
            $tahunAjaran = tahun_ajaran::where('now', false)->get();

             // Loop setiap TahunAjaran dan hapus data PakKegiatanPendidikanDanPengajaran yang terkait
                foreach ($tahunAjaran as $tahun) {
                    $pakKegiatan1 = $tahun->pak_kegiatan_pendidikan_dan_pengajaran; # pak_kegiatan_pendidikan_dan_pengajaran ini model
                    $pakKegiatan2 = $tahun->pak_kegiatan_penelitian; # pak_kegiatan_penelitian ini model
                    $pakKegiatan3 = $tahun->pak_kegiatan_pengabdian_pada_masyarakat; # pak_kegiatan_pengabdian_pada_masyarakat ini model
                    $pakKegiatan4 = $tahun->pak_kegiatan_penunjang_tri_dharma_pt; # pak_kegiatan_penunjang_tri_dharma_pt ini model

                    // Hapus file PDF yang terkait
                        foreach ($pakKegiatan1 as $data) {
                            if ($data->bukti) {
                                Storage::delete($data->bukti);
                            }
                        }

                    // Hapus file PDF yang terkait
                        foreach ($pakKegiatan2 as $data) {
                            if ($data->bukti) {
                                Storage::delete($data->bukti);
                            }
                        }

                    // Hapus file PDF yang terkait
                        foreach ($pakKegiatan3 as $data) {
                            if ($data->bukti) {
                                Storage::delete($data->bukti);
                            }
                        } 

                    // Hapus file PDF yang terkait
                        foreach ($pakKegiatan4 as $data) {
                            if ($data->bukti) {
                                Storage::delete($data->bukti);
                            }
                        }


                    // Hapus data 
                    $tahun->pak_kegiatan_pendidikan_dan_pengajaran()->delete();
                    $tahun->pak_kegiatan_penelitian()->delete();
                    $tahun->pak_kegiatan_pengabdian_pada_masyarakat()->delete();
                    $tahun->pak_kegiatan_penunjang_tri_dharma_pt()->delete();
                }
            Alert::success('success', 'Data tahun ajaran yang tidak aktif berhasil dihapus');
            return redirect()->back();
        
    }

}
