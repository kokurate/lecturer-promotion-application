<?php

namespace App\Http\Controllers;

use App\Mail\DosenNotifications;
use App\Models\berkas_kenaikan_pangkat_reguler;
use App\Models\history;
use App\Models\kategori_pak;
use App\Models\pak_kegiatan_pendidikan_dan_pengajaran;
use App\Models\pak_kegiatan_penelitian;
use App\Models\pak_kegiatan_pengabdian_pada_masyarakat;
use App\Models\pak_kegiatan_penunjang_tri_dharma_pt;
use App\Models\tahun_ajaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;


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

    public function simulation_download()
    {

        $user = Auth::user();

        // Banyaknya
            $banyaknya = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->where('kegiatan','!=', NULL)->count('id') + 
                        pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->count('id') +
                        pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->count('id') +
                        pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->count('id') ;
        
            $total_kredit = pak_kegiatan_pendidikan_dan_pengajaran::where('user_id', auth()->user()->id)->sum('angka_kredit') +
                            pak_kegiatan_penelitian::where('user_id', auth()->user()->id)->sum('angka_kredit') +
                            pak_kegiatan_pengabdian_pada_masyarakat::where('user_id', auth()->user()->id)->sum('angka_kredit') +
                            pak_kegiatan_penunjang_tri_dharma_pt::where('user_id', auth()->user()->id)->sum('angka_kredit') ;

                            $title = 'Dosen | Simulasi';
                       
                            $kategori_pak = kategori_pak::withCount([
                                'pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id)->where('kegiatan', '!=', NULL);
                                },
                                'pak_kegiatan_penelitian' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                },
                                'pak_kegiatan_pengabdian_pada_masyarakat' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                },
                                'pak_kegiatan_penunjang_tri_dharma_pt' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                }
                            ])
                            ->with([
                                'pak_kegiatan_pendidikan_dan_pengajaran' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                },
                                'pak_kegiatan_penelitian' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                },
                                'pak_kegiatan_pengabdian_pada_masyarakat' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                },
                                'pak_kegiatan_penunjang_tri_dharma_pt' => function ($query) use ($user) {
                                    $query->where('user_id', $user->id);
                                }
                            ])

                            ->get();


        $pdf = Pdf::loadView('pdf.export-simulasi', [
            'kategori_pak' => $kategori_pak,
            'title' => $title,
            'total_kegiatan' => $banyaknya,
            'jumlah_kredit' => $total_kredit, 
            // PENDIDIKAN DAN PENGAJARAN ===================================
                'all' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->with('tahun_ajaran')->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                                                                    ->whereHas('tahun_ajaran', function ($query) {
                                                                                                        $query->where('now', true);
                                                                                                    })
                                                                                    ->orderBy('id', 'DESC')
                                                                                    ->get(),
                'tahun_ajaran' => tahun_ajaran::where('now', true)->get(),
                'total_kredit' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)->sum('angka_kredit'),
                'pendidikan_formal'=> pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                        ->where('tipe_kegiatan','Mengikuti Pendidikan Formal')->count(),
                'diklat_pra_jabatan' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                        ->where('tipe_kegiatan','Mengikuti Diklat Pra-Jabatan')->count(),
                'total_sks' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                        ->where('tipe_kegiatan', 'Melaksanakan Perkuliahan')->where('komponen_kegiatan', 'Mengajar')->sum('sks'),
                'pendidikan_dokter_klinis' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where(function ($query) {
                                            $query->where('kode', 'II.A.3.a')
                                                ->orWhere('kode', 'II.A.3.b')
                                                ->orWhere('kode', 'II.A.3.c')
                                                ->orWhere('kode', 'II.A.3.d')
                                                ->orWhere('kode', 'II.A.3.e');
                                        })->sum('angka_kredit'),      
                'membimbing_seminar_mahasiswa' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                            ->where('tipe_kegiatan','Membimbing Seminar Mahasiswa (Setiap Mahasiswa)')->count(),
                'membimbing_kkn_dst' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id', 1)
                                            ->where('tipe_kegiatan','Membimbing KKN, Praktik Kerja Nyata, Praktik Kerja Lapangan')->count(),
                // Pembimbing 1 Count
                    'p1_disertasi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.D.1.a')->count(),
                    'p1_tesis' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.D.1.b')->count(),
                    'p1_skripsi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.D.1.c')->count(),
                    'p1_laporan_akhir_studi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                    ->where('kode','II.D.1.d')->count(),
                // Pembimbing 2 Count
                    'p2_disertasi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                ->where('kode','II.D.2.a')->count(),
                    'p2_tesis' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.D.2.b')->count(),
                    'p2_skripsi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.D.2.c')->count(),
                    'p2_laporan_akhir_studi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.D.2.d')->count(),
                                                        

                    'ketua_penguji' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.E.1')->count(),
                    'anggota_penguji' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.E.2')->count(),
                    'membina_kegiatan_mahasiswa' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.F')->count(),
                    'mengembangkan_program_kuliah' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.G')->count(),
                    'buku_ajar' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.H.1')->count(),
                    'diklat_modul' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.H.2')->count(),
                    'orasi_ilmiah' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.I')->count(),
                    'menduduki_jabatan' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where(function ($query) { $query ->where('kode', 'II.J.1')
                                                                                        ->orWhere('kode','II.J.2') 
                                                                                        ->orWhere('kode','II.J.3') 
                                                                                        ->orWhere('kode','II.J.4') 
                                                                                        ->orWhere('kode','II.J.5') 
                                                                                        ->orWhere('kode','II.J.6') 
                                                                                        ->orWhere('kode','II.J.7') 
                                                                                        ->orWhere('kode','II.J.8'); 
                                                                                    })->count(),
                    'pembimbing_pencangkokan' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.K.1')->count(),
                    'pembimbing_reguler' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.K.2')->count(),
                    'detasering_luar_instansi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.L.1')->count(),
                    'pencangkokan_luar_instansi' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where('kode','II.L.2')->count(),
                    'pengembangan_diri' => pak_kegiatan_pendidikan_dan_pengajaran::with('tahun_ajaran')->whereHas('tahun_ajaran', function ($query) {$query->where('now', true);})->where('user_id', auth()->user()->id)->where('kategori_pak_id',1)
                                                        ->where(function ($query) { $query ->where('kode', 'II.M.1')
                                                                                        ->orWhere('kode','II.M.2') 
                                                                                        ->orWhere('kode','II.M.3') 
                                                                                        ->orWhere('kode','II.M.4') 
                                                                                        ->orWhere('kode','II.M.5') 
                                                                                        ->orWhere('kode','II.M.6') 
                                                                                        ->orWhere('kode','II.M.7'); 
                                                                                    })->count(),
            // PENELITIAN =========================================                                                                   
                # 1 a)    
                'k_i__buku_refrensi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.1')->sum('angka_kredit'),
                'k_i__monograf' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.2')->sum('angka_kredit'),
                # 1 b)
                'buku_internasional' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.2.1')->sum('angka_kredit'),
                'buku_nasional' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.a.2.2')->sum('angka_kredit'),
                # 1 c)
                'jurnal_int_bereputasi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.1.1')->sum('angka_kredit'),
                'jurnal_int_terindek_db_bereputpasi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.1.2')->sum('angka_kredit'),
                'jurnal_int_terindek_db_int_luar_kategori_2' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.1.3')->sum('angka_kredit'),
                'jurnal_nas_terakreditasi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.2')->sum('angka_kredit'),
                'jurnal_nas_bhs_indonesia_doaj' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.2.1')->sum('angka_kredit'),
                'jurnal_nas_bhs_inggris_doaj' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.2.2')->sum('angka_kredit'),
                'jurnal_nasional' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.3')->sum('angka_kredit'),
                'jurnal_bhs_resmi_pbb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.b.3.1')->sum('angka_kredit'),
                # 2 a
                'dimuat_dalam_prosiding_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.a.1')->sum('angka_kredit'),
                'dimuat_dalam_prosiding_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.2.b')->sum('angka_kredit'),
                # 2 b
                'poster_dalam_prosiding_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.2.a')->sum('angka_kredit'),
                'poster_dalam_prosiding_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.b.1')->sum('angka_kredit'),
                # 2 c
                'int_tanpa_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.a')->sum('angka_kredit'),
                'nas_tanpa_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.1.b')->sum('angka_kredit'),
                # 2 d
                'int_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.3.a')->sum('angka_kredit'),
                'nas_prosiding_disajikan_dalam_seminar_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.c.3.b')->sum('angka_kredit'),
                # 2 e
                'disajikan_dalam_koran_majalah_dsb' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.1.d')->sum('angka_kredit'),
                # 3 
                'hasil_penelitian_tidak_dipublikasikan_tersimpan_perpustakaan' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.A.2')->sum('angka_kredit'),
                # 4
                'menerjemahkan_buku_ilmiah_isbn' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.B')->sum('angka_kredit'),
                # 5 
                'menyunting_karya_ilmiah_bentuk_buku_isbn' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.C')->sum('angka_kredit'),
                # 6 
                'paten_rancangan_teknologi_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.D.1')->sum('angka_kredit'),
                'paten_rancangan_teknologi_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.D.2')->sum('angka_kredit'),
                # 7
                'tanpa_paten_rancangan_teknologi_int' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.1')->sum('angka_kredit'),
                'tanpa_paten_rancangan_teknologi_nas' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.2')->sum('angka_kredit'),
                'tanpa_paten_rancangan_teknologi_lokal' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.3')->sum('angka_kredit'),
                # 8
                'tanpa_hki_rancangan_teknologi' => pak_kegiatan_penelitian::QueryCount()->QueryKode('II.E.4')->sum('angka_kredit'),

        ]);
        return $pdf->download('simulasi-'.auth()->user()->name);

    }
}
