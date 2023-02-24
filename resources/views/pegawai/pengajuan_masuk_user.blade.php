@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">
    <section class="section">
        <div class="row">
            <div class="row">
                    <div class="col-lg">
                        <div class="card">
                            <div class="card-body">
                                <br>
                                <h2 class="my-3 text-center" style="color:#012970;"><strong>Nama : {{ $user->name }}</strong></h2>
                                <h2 class="my-3 text-center" style="color:#012970;"><strong>NIP : {{ $user->nip }}</strong></h2>
                                <h2 class="my-3 text-center" style="color:#012970;"><strong>NIDN : {{ $user->nidn }}</strong></h2>
                                <br>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('pegawai.pengajuan_masuk_detail', $user->email) }}" class="my-2 btn btn-success rounded-pill" style="background-color:#0fcc08;border:none;">Buka Pengajuan Kenaikan Pangkat</a>
                                </div>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('pegawai.pengajuan_masuk') }}" class="my-2 btn btn-success rounded-pill" style="background-color:#f0fc03;border:none;color:#012970;">Kembali</a>
                                </div>
                                
                                
                        </div>
                    </div>
            </div>
        </div>
    </section>
</main>



@endsection