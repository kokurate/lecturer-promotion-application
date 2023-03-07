<?php

namespace App\Http\Controllers;

use App\Models\fakultas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

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

}
