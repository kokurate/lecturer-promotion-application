<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DosenController extends Controller
{
    public function index(){
        return view('dosen.index',[
            'title' => 'Dosen Dashboard',
        ]);
    }

    public function tambah_pangkat_reguler(){
        return view('dosen.tambah_pangkat_reguler',[
            'title' => 'Unggah Berkas'
        ]);
    }

    public function tambah_pangkat_reguler_store(){

    }

    public function storage(){
        return view('dosen.storage',[
            'title' => 'Dosen | Storage'
        ]);
    }

}
