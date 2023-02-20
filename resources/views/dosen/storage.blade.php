@extends('layouts.master')

@section('content')

@include('layouts.header')

<main id="main" class="main">


    <section class="section">
      <div class="row">
        <div class="col-lg">

          <div class="card">
            <div class="card-body">
              <h2 class="mt-5 text-center" style="color:#012970;"><strong>Penyimpanan Anda</strong></h2>
              <a href="#" id="showDiv" class="btn btn-outline-success btn-lg rounded-pill" style="padding-left: 20px; padding-right: 20px;"><i class="bi bi-plus-circle"></i> Tambah</a>
              
            <div id="myDiv" style="display: none;">
              <form action="{{ route('dosen.storage_store') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-6">
                    <div class="col">
                        <label for="inputNanme4" class="form-label"></label>
                        <input class="form-control" type="file" id="formFile" name="filename"  accept=".pdf">
                        
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary my-3" style="color:#ffffff;">Submit</button>
                        </div>
                    </div>
                </div>
              </form>  
            </div>   

            </div>
          </div>

        </div>
      </div>

      <!-- =========== New Row ========== -->
        <div class="row">
            <div class="col-lg">
                <h2>testing</h2>
            </div>
        </div>

    </section>
</main><!-- End #main -->
  

@endsection

@push('script')

    <script>
        var showDivButton = document.getElementById('showDiv');
        var myDiv = document.getElementById('myDiv');
        var divVisible = false;

        showDivButton.addEventListener('click', function() {
        if (divVisible) {
            myDiv.style.display = 'none';
            divVisible = false;
        } else {
            myDiv.style.display = 'block';
            divVisible = true;
        }
        });
    </script>
@endpush