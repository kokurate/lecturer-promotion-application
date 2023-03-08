@extends('layouts.master')

@section('content')

@include('layouts.header')
  


  <main id="main" class="main">


    <section class="section">
      <div class="row">
        <div class="col-lg">

          <div class="card">
            <div class="card-body">
              <h2 class="mt-5" style="color:#012970;">Selamat Datang, <strong>Staf Kepegawaian</strong></h2>
              <h3 style="color:#012970;">{{ auth()->user()->fakultas }}</h3>
              <p style="margin-top: 25px;"></p>
              <p><strong>Kenaikan Pangkat Dalam Proses : <span style="color:#00ff66">
                {{ $dalam_proses }}
              </span></strong></p>
            </div>
          </div> <!-- End Card -->

        </div>

      </div> <!-- End Row--> 

      <!-- New Chart with new Row ===================================== -->
        <div class="row">
          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Semua Pengajuan</h5>

                <!-- Pie Chart -->
                <canvas id="pieChart" style="max-height: 400px;"></canvas>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#pieChart'), {
                      type: 'pie',
                      data: {
                        labels: [
                          'Masuk',
                          'Ditolak',
                          'Selesai',
                          'Diproses',
                        ],
                        
                        datasets: [{
                          // label: 'My First Dataset',
                          data: [{{ $all_masuk }}, {{ $all_ditolak }}, {{ $all_selesai }}, {{ $all_diproses }}],
                          backgroundColor: [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                            'rgb(0, 255, 60)',
                            'rgb(255, 238, 0)',
                          ],
                          
                          hoverOffset: 4
                        }]
                      }
                    });
                  });
                </script>
                <!-- End Pie CHart -->

              </div>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Pengajuan Bulan Ini</h5>
  
                <!-- Bar Chart -->
                <canvas id="barChart" style="max-height: 400px;"></canvas>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    new Chart(document.querySelector('#barChart'), {
                      type: 'bar',
                      data: {
                        labels: [ 'Masuk','Ditolak','Selesai','Diproses',],
                        datasets: [{
                          label:  '{{ auth()->user()->fakultas }}',
                          data: [{{ $this_month_masuk }}, {{ $this_month_ditolak }}, {{ $this_month_selesai }}, {{ $this_month_diproses }}],
                          backgroundColor: [
                            'rgb(54, 162, 235)',
                            'rgb(255, 99, 132)',
                            'rgb(0, 255, 60)',
                            'rgb(255, 238, 0)',
                          ],
                          borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                          ],
                          borderWidth: 1
                        }]
                      },
                      options: {
                        scales: {
                          y: {
                            beginAtZero: true
                          }
                        }
                      }
                    });
                  });
                </script>
                <!-- End Bar CHart -->
  
              </div>
            </div>
          </div>
  
        </div> <!-- End New Row-->
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer" style="margin-top:60px">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>


@endsection