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
                        <label for="nama" class="mt-3 form-label"><strong>Nama File</strong></label>
                        <input class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}" type="text" name="nama" value="{{ old('nama') }}" required>
                        
                        <label for="inputNanme4" class="form-label"></label>
                        <input class="form-control{{ $errors->has('path') ? ' is-invalid' : '' }}" type="file" id="pdf_file" name="path" accept=".pdf">

                        @if ($errors->has('path'))
                            <div class="invalid-feedback">
                                {{ $errors->first('path') }}
                            </div>
                        @endif
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary my-3" style="color:#ffffff;">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="col">
                        <h4 class="text-center" ><strong>Preview File</strong></h4>
                        <div id="preview" class="my-2"></div>
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
                    <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder opacity-7 ">#</th>
                            <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder  ps-2">Nama File</th>
                            <th class="text-center text-uppercase text-dark text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                        </tr>
                        </thead>
                            <tbody>
                            @forelse($all_storage as $all)
                                <tr>
                                    <td>
                                        {{-- <a href="{{ route('pdf-files.show', $all->filename) }}" class="text-secondary text-xs"> --}}
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ asset('storage/'. $all->path) }}" class="text-secondary text-xs" target="__blank">
                                                <i class="fas fa-solid fa-file-pdf ps-3"></i> Open PDF
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                    <p class="text-center text-xs  mb-0">{{ $all->nama }}</p>
                                    </td>
                                    <td>
                                    <div class="d-flex justify-content-center ">
                                            <form action="{{ route('dosen.storage_destroy', $all->filename) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button class="text-decoration-none border-0 p-0 m-0" type="submit" onclick="confirmDelete(event)">
                                                    <div class="d-flex justify-content-center badge bg-danger">
                                                        <i class="text-light bi bi-trash"></i>
                                                    </div>
                                                </button>
                                            </form>
                                    </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center"><p class="font-weight-bold my-2">Tidak ada file pada penyimpanan anda </p></td>
                                </tr>
                            @endforelse
                            </tbody>
                    </table>
                    </div> <!-- End Table -->
            </div>
        </div>

        <br><br><br><br><br>

    </section>
</main><!-- End #main -->
  

@endsection

@push('script')

    <!-- Delete Trigger -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <script>
            function confirmDelete(event) {
                event.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda yakin ingin menghapus data ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.closest('form').submit();
                    }
                });
            }
        </script>

    <script>
        // show the form
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
        
    <script>
        // PDF Preview
        const fileInput = document.querySelector('#pdf_file');
        const preview = document.querySelector('#preview');
    
        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            const reader = new FileReader();
    
            reader.onload = (e) => {
                const pdfUrl = e.target.result;
                const iframe = document.createElement('iframe');
    
                iframe.src = pdfUrl;
                iframe.width = '100%';
                iframe.height = '600';
    
                preview.innerHTML = '';
                preview.appendChild(iframe);
            };
    
            reader.readAsDataURL(file);
        });
    </script>
@endpush