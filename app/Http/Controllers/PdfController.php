<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

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
        $validator = Validator::make($request->all(),[
            'kartu_pegawai_nip_baru_bkn' => 'nullable',
            'sk_cpns' => 'nullable',
            'sk_pangkat_terakhir' => 'nullable',
        ]);

    // Validasi
    $validatedData = $validator->validated();
    $oMerger = PDFMerger::init();
    // dd($validatedData);
    
    $oMerger->addPDF(storage_path('app/public/' . substr($validatedData['kartu_pegawai_nip_baru_bkn'], strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($validatedData['sk_cpns'], strlen(asset('storage/')))),'all');
    $oMerger->addPDF(storage_path('app/public/' . substr($validatedData['sk_pangkat_terakhir'], strlen(asset('storage/')))),'all');
    

    $oMerger->merge();
    $oMerger->save(public_path('merged_result.pdf'));

    // return response()->download(public_path('merged_result.pdf'));

    }
}
