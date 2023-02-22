<?php

namespace App\Http\Controllers;

use App\Models\jurusan_prodi;
use App\Models\pangkat;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

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
        $validatedData['password'] = bcrypt(Str::random('5')); 
        $validatedData['level'] = 'dosen'; 
        $validatedData['fakultas'] = auth()->user()->fakultas; 

        User::create($validatedData);
        // dd($validatedData);
        
        Alert::success('Data Berhasil Ditambahkan');
        return back();
    }
}
