@extends('layouts.master')

@section('content')

@include('layouts.header')

    <main id="main" class="main">


        <section class="section">
        <div class="row">
            <div class="col-lg">

            <div class="card">
                <div class="card-body">
                <h2 class="mt-5" style="color:#012970;">Selamat Datang, <strong>Admin</strong></h2>
                <h3 class="" style="color:#012970;">{{ auth()->user()->name }}</h3>
                <br>
                <h5 style="color:#012970;">Jumlah Pegawai : {{ $count_pegawai }}</h5>
                <h5 style="color:#012970;">Jumlah Dosen : {{ $count_dosen }}</h5>
                <p style="margin-top: 25px;"></p>
                
                </div>
            </div>

            </div>

        </div>
        </section>

    </main><!-- End #main -->
<br>
<br>
<br>
    @include('layouts.footer')

@endsection
