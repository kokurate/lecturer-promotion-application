<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        @if(auth()->user()->level == 'admin')
          <a href="{{ route('admin.index') }}" class="logo d-flex align-items-center">
        @elseif(auth()->user()->level == 'dosen')
          <a href="{{ route('dosen.index') }}" class="logo d-flex align-items-center">
        @elseif(auth()->user()->level == 'pegawai')
          <a href="{{ route('pegawai.index') }}" class="logo d-flex align-items-center">
        @endif
        {{-- <img src="/templates/assets/img/logo.png" alt=""> --}}
        <span class="d-none d-lg-block my-1 fs-4">Pengelolaan Data Dosen</span>
    
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

 

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        



        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            {{-- <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle"> --}}
            <img src="/logo.png" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">Setting</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{{ auth()->user()->name }}</h6>
              @if(auth()->user()->level == 'pegawai' || auth()->user()->level == 'dosen')
                  <span>{{ auth()->user()->fakultas }}</span>
                @if(auth()->user()->level == 'dosen')
                  <br>
                  <span>{{ auth()->user()->jurusan_prodi }}</span>
                @endif
              @else
              <span>Admin</span>
              @endif
            </li>

            {{-- <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="users-profile.html">
                <i class="bi bi-person"></i>
                <span>My Profile</span>
              </a>
            </li>

          
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="pages-faq.html">
                <i class="bi bi-question-circle"></i>
                <span>Need Help?</span>
              </a>
            </li> --}}


            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
                <!-- ubahpass -->
                <a class="dropdown-item d-flex align-items-center" href="{{ route('change_password') }}">
                    <i class="bi bi-gear"></i>
                    <span>Ubah Password</span>
                </a>
            </li>


            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="#">
                <i class="bi bi-box-arrow-right"></i>
                <span>
                <form action="{{ route('logout') }}" method="post">
                  @csrf
                  {{-- <button type="submit" class="" style="text-decoration: none; border:none; border-radius:none;">Log Out</button> --}}
                  <button type="submit" class="btn text-decoration-none border-0 p-0 m-0">Log Out</button>

                </form> 
              </span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->
  

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <!-- ============ Dosen  ================= -->
      @if(auth()->user()->level == 'dosen'  )
      <li class="nav-item  ">
        <a class="nav-link collapsed {{ request()->routeIs('dosen.index') ? 'bg-secondary text-light' : '' }}" href="{{ route('dosen.index') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard Dosen</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed {{ Request::is('dosen/storage*') ? 'bg-secondary text-light' : '' }}" href="{{ route('dosen.storage') }}">
          <i class="bi bi-sd-card"></i>
          <span>Storage</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed {{ Request::is('dosen/simulasi*') ? 'bg-secondary text-light' : '' }}" href="{{ route('dosen.simulasi') }}">
          <i class="bi bi-question-diamond"></i>
          <span>Simulasi</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @endif
      

      <!-- ============ Pegawai ================= -->
      @if(auth()->user()->level == 'pegawai')
      <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('pegawai.index') ? 'bg-secondary text-light' : '' }}" href="{{ route('pegawai.index') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard Pegawai</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed {{ request()->routeIs('pegawai.permintaan_kenaikan_pangkat') ? 'bg-secondary text-light' : '' }}" href="{{ route('pegawai.permintaan_kenaikan_pangkat') }}">
          <i class="bi bi-circle"></i>
          <span>Permintaan Kenaikan</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Data Pengajuan</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('pegawai.semua_dosen') }}">
              <i class="bi bi-circle"></i><span>Semua Dosen</span>
            </a>
          </li>
          <li>
            <a href="{{ route('pegawai.pengajuan_masuk') }}">
              <i class="bi bi-circle"></i><span>Masuk</span>
            </a>
          </li>
          <li>
            <a href="{{ route('pegawai.pengajuan_dalam_proses') }}">
              <i class="bi bi-circle"></i><span>Dalam Proses</span>
            </a>
          </li>
          <li>
          <li>
            {{-- <a href="#">
              <i class="bi bi-circle"></i><span>Selesai</span>
            </a>
          </li>
          <li> --}}
      @endif


      <!-- ============ Admin ================= -->
      @if(auth()->user()->level == 'admin')
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.index') }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard Admin</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @endif

      



    
      @if(auth()->user()->level == 'admin')
        <li class="nav-heading">Admin Pages</li>

        <li class="nav-item">
          <a class="nav-link collapsed" href="{{ route('admin.register_pegawai') }}">
            <i class="bi bi-person"></i>
            <span>Register Pegawai</span>
          </a>
        </li><!-- End Profile Page Nav -->

        <li class="nav-item">
          <a class="nav-link collapsed {{ Request::is('admin/simulasi*') ? 'bg-secondary text-light' : '' }}" href="{{ route('admin.simulasi') }}">
            <i class="bi bi-question-diamond"></i>
            <span>Simulasi</span>
          </a>
        </li><!-- End Dashboard Nav -->
      @endif

    </ul>

  </aside><!-- End Sidebar-->

@push('script')
  <script>
       // Modal    
  </script>
  

@endpush