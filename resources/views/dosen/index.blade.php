@extends('layouts.master')

@section('content')

@include('layouts.header')
  


  <main id="main" class="main">


    <section class="section">
      <div class="row">
        <div class="col-lg">

          <div class="card">
            <div class="card-body">
              <h2 class="mt-5" style="color:#012970;">Selamat Datang, <strong>{{ auth()->user()->name }}</strong></h2>
              <?php
                $valuefakultas = auth()->user()->fakultas;
                $substringsfakultas = explode(' ', $valuefakultas);
                $fakultas = $substringsfakultas[0]; 

                $valueafterfakultas = auth()->user()->fakultas;
                $substringsafterfakultas = explode(' ', $valueafterfakultas);
                $afterfakultas = implode(' ', array_slice($substringsafterfakultas, 1))
              ?>
              <h3 style="color:#012970;">Dosen {{ auth()->user()->jurusan_prodi }}, {{ $fakultas }} <strong>{{ $afterfakultas }}</strong></h3>
              <p style="margin-top: 25px;"></p>
              <p><strong>Kenaikan Pangkat  : 
                <span style="color:#00ff66">Tersedia</span>
                <span style="color:#cc0808">Tersedia</span>
              </strong></p>
              
        <div class="text-center my-5">
            <div class="text-align-center">
                <a href="#" class="btn btn-primary btn-lg rounded-pill" style="background-color:#012970; padding-left: 50px; padding-right: 50px;">Ajukan Kenaikan <br> Pangkat</a>
                <a href="#" class="btn btn-success btn-lg rounded-pill" style="background-color:#0fcc08; padding-left: 50px; padding-right: 50px;">Lihat Status <br> Kenaikan Pangkat</a>
            </div>
        </div>
         

            </div>
          </div>

        </div>

      </div>
    </section>

  </main><!-- End #main -->

  @include('layouts.footer')
 
@endsection