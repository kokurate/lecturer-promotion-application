<?php

namespace App\Http\Controllers;

use App\Models\my_storage;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            return redirect()->back()->withErrors($validator)->withInput()->with('error', 'Gagal mengunggah file. Pastikan file yang diunggah berformat PDF dan berukuran maksimal 1MB.');
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

}
