<?php

namespace App\Http\Controllers;

use App\Mail\SendingForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

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

    public function show_forgot_password(){

        return view('auth.forgot-password',[
            'title' => 'Forgot Passowrd'
        ]);
    }

    public function store_forgot_password(Request $request){
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|exists:users,email',
        ],[
            'email.required' => 'Email Harus Diisi',
            'email.email' => 'Harus format email',
            'email.exists' => 'Email belum terdaftar',

        ]);
        
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal Menambahkan Akun Dosen');
        }

        // Validasi
        $validatedData = $validator->validated();
        $validatedData['my_token'] = Str::random('255');
        
        // cari email kalo sesuai langsung update depe token
        User::where('email', $validatedData['email'])->update(['my_token' => $validatedData['my_token'],]);

        // prepare for sending email reset password
      
        $data = [
                    'title' => 'Link Reset Password',
                    'content' => 'Berikut merupakan link reset password anda yang baru. 
                                    Jika anda tidak melakukan reset password, harap abaikan pesan ini',
                    'expire' => 'Token Ini akan hangus dalam waktu 3 jam dan hanya bisa digunakan untuk sekali reset pasword', 
                    'url' => route('show_reset_password', $validatedData['my_token']),
                ];
        
        // Send to Email
        Mail::to($validatedData['email'])->send(new SendingForgotPassword ($data));

        Alert::success('Berhasil','Silahkan Cek Email Anda Untuk Melakukan Reset Password');

        return redirect()->back();
    }

    public function show_reset_password(User $user){
        return view('auth.reset-password',[
            'title' => 'Reset Password',
            'user' => $user,
        ]);

    }

    public function store_reset_password(Request $request, User $user){
       
        $validator = Validator::make($request->all(),[
            'password' => 'required|confirmed|min:8',
        ],[
            'password.required' => 'Password harus diisi',
            'password.confirmed' => 'Password tidak cocok',
            'password.min' => 'Password minimal 8 karakter',

        ]);
        
        if ($validator->fails()) {
            Alert::error($validator->errors()->all()[0]);
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal mengubah password');
        }

        // Validasi
        $validatedData = $validator->validated();
        $validatedData['password'] = Hash::make($validatedData['password']);

        // dd($validatedData);
        User::where('email', $user->email)->update(['password' => $validatedData['password'], 'my_token' => null]);
        
        Alert::success('Reset Password Berhasil','Silahkan login menggunakan password baru anda');
        return redirect()->route('login');
    }




}
