<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class PdfController extends Controller
{
    public function show($path)
    {
        $fullPath = storage_path('app/public/' . $path);

        if (!Storage::exists($fullPath)) {
            abort(404);
        }
    
        return response()->file($fullPath);
    }

    public function merge(Request $request, User $user){
        // dd($request);
    //     $validator = Validator::make($request->all(),[
    //         'kartu_pegawai_nip_baru_bkn' => 'nullable',
    //         'sk_cpns' => 'nullable',
    //         'sk_pangkat_terakhir' => 'nullable',
    //     ]);

    // // Validasi
    // $validatedData = $validator->validated();
    $oMerger = PDFMerger::init();
    // dd($validatedData);
    
    // $oMerger->addPDF(storage_path('app/public/' . substr($validatedData['kartu_pegawai_nip_baru_bkn'], strlen(asset('storage/')))),'all');
    // $oMerger->addPDF(storage_path('app/public/' . substr($validatedData['sk_cpns'], strlen(asset('storage/')))),'all');
    // $oMerger->addPDF(storage_path('app/public/' . substr($validatedData['sk_pangkat_terakhir'], strlen(asset('storage/')))),'all');
    
    User::where('id', $user->id)->update(['status' => 'Disetujui']);

    $oMerger->addPDF(storage_path('app/public/' . substr($request->kartu_pegawai_nip_baru_bkn, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->sk_cpns, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->sk_pangkat_terakhir, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->sk_jabfung_terakhir_dan_pak, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->ppk_dan_skp, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->ijazah_terakhir, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->sk_tugas_belajar_atau_surat_izin_studi, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->keterangan_membina_mata_kuliah_dari_jurusan, strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($request->surat_pernyataan_setiap_bidang_tridharma, strlen(asset('storage/')))),'all');



    $namadosen = $user->name;
    $slug = Str::slug($namadosen);

    $fileName = '/storage/dosen/'.$slug.'/'. time().'-merge'.'.pdf';

    $oMerger->merge();
    $oMerger->save(public_path($fileName));

    return response()->download(public_path($fileName));

    }
}
