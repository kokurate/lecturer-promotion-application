<?php

namespace App\Http\Controllers;

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
        return view('pegawai.semua_dosen',[
           'title' => 'Semua Dosen Fakultas',
           'pangkat' => pangkat::all(), 
        ]);
    }

    public function semua_dosen_store(Request $request){
        $validator = Validator::make($request->all(),[ 
            'name' => 'required|max:255|string',
            'email' => 'required|email',
            // 'jurusan_prodi' => 'required',
            'pangkat_id' => 'required|exists:pangkats,id',
         ],[
            'pangkat_id.exists' => 'Golongan Belum dipilih'
         ]);

         if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Validasi
        $validatedData = $validator->validated();
        $validatedData['password'] = bcrypt(Str::random('5')); 
        $validatedData['level'] = 'dosen'; 

        User::create($validatedData);
        // dd($validatedData);
        
        Alert::success('Data Berhasil Ditambahkan');
        return back();
    }
}
