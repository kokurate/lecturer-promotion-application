<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('auth.login',[
            'title' => 'Login Page' 
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ],
        [
            'email.required' => 'Email harus diisi',
            'password,required' => 'Password harus diisi'
        ]);
        // jika login berhasil
        if(Auth::attempt($credentials)){
            // cek level
            $user = Auth::user();
            if($user->level == 'admin'){
                $request->session()->regenerate();
                return redirect()->intended('admin');
            }
            elseif($user->level == 'pegawai'){
                $request->session()->regenerate();
                return redirect()->intended('pegawai');
            }
            elseif($user->level == 'dosen'){
                $request->session()->regenerate();
                return redirect()->intended('dosen');
            }
            // jika tidak ada levelnya maka akan diredirect ke login
            return redirect()->intended('/')->with('gagal', 'Login Gagal');
        }else
        // Melakukan login tapi tidak ada di database
        return back()->with('gagal','Error');
    }

    public function logout(Request $request){
        Auth::logout($request);
        
        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        $request->session()->flash('berhasil', 'Anda Berhasil Keluar');
        return redirect()->route('login');
    
    }




}
