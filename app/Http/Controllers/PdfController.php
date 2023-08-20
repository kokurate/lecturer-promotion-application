<?php

namespace App\Http\Controllers;

use App\Mail\DosenNotifications;
use App\Models\berkas_kenaikan_pangkat_reguler;
use App\Models\history;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;

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
        $oMerger = PDFMerger::init();

        
        User::where('id', $user->id)->update(['status' => 'Disetujui']);

        //preparing history
        $data_history = [
            'status' => 'Disetujui',
            // 'user_id' => null,
        ];

        // Buat history
        history::where('user_id', $user->id)->update($data_history);



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

        $generate = $slug.'/'. time().'-merge'.'.pdf';
        $fileName = '/storage/dosen/'. $generate;

        $oMerger->merge();
        $oMerger->save(public_path($fileName));
        

        // sebelum buat cek dulu kalo ada isi hapus merge
        $take_berkas = new berkas_kenaikan_pangkat_reguler();
        $take_id = $take_berkas->where('user_id', $user->id)->whereNotNull('merge')->first();
        // dd($take_id);
        if($take_id){
            Storage::delete($take_id->merge);
            berkas_kenaikan_pangkat_reguler::destroy($take_id->merge);
        }



        $mergelokasi = 'dosen/'. $generate;
        berkas_kenaikan_pangkat_reguler::where('user_id', $user->id)->update(['merge' => $mergelokasi]);

        $data = [
            'title' => 'Notifikasi Dosen',
            'open' => 'Status Kenaikan Pangkat Anda Diperbarui Menjadi Disetujui', 
            'close' =>  'Silahkan cek website untuk informasi lebih lanjut',
            'url' => route('login'),
        ];

        // Send to Email
        Mail::to($user->email)->send(new DosenNotifications($data));

        Alert::success('Berhasil','Pengajuan Berhasil Diterima, Harap Segera Mendownload File PDF di bawah ini');

        // return response()->download(public_path($fileName));
        return redirect()->back()->with('success', 'Pengajuan Berhasil Diterima');

    }
}
