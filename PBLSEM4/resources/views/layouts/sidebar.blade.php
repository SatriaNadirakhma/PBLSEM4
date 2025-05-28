<div class="sidebar">
    <!-- SidebarSearch Form -->
     <div class="form-inline mt-2">
        <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div> 

    <!-- <div class="mb-3 mt-2"> -->
        <!-- Tombol toggle collapse -->
        <!-- <button class="btn bg-dark w-100 text-start d-flex align-items-center justify-content-between" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#infoCollapse" 
                aria-expanded="true" 
                aria-controls="infoCollapse">
            <span class="d-flex align-items-center">
                <i class="fas fa-info-circle me-2 mr-3 text-white"></i>
                <strong class="text-white">Informasi</strong>
            </span>
            <i class="fas fa-chevron-down text-white"></i>
        </button> -->

        <!-- Konten collapse -->
        <!-- <div class="collapse show" id="infoCollapse">
            <div class="alert mb-0 border-start border-2 border-light bg-dark text-white rounded-0">
                <small>User bisa mengakses menu yang ada di sidebar berikut</small>
            </div>
        </div>
    </div> -->




    <!-- Sidebar Menu -->
    <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
            <li class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <!--DATA USER -->
            @auth
            @if(Auth::user()->role == 'admin')
            <li class="nav-item mt-1">
                <a href="{{ route('user') }}" class="nav-link {{ in_array($activeMenu, ['user']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>DATA USER</p>
                </a>
            </li>
            @endif
            @endauth


            <!-- Dropdown BIODATA -->
            @auth
            @if(Auth::user()->role == 'admin')
            <li class="nav-item mt-1 has-treeview {{ in_array($activeMenu, ['peserta-mahasiswa', 'peserta-dosen', 'peserta-tendik', 'admin']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['peserta-mahasiswa', 'peserta-dosen', 'peserta-tendik', 'admin']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        BIODATA USER
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ url('/biodata/mahasiswa') }}" class="nav-link {{ ($activeMenu == 'peserta-mahasiswa') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Peserta Mahasiswa</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ url('/biodata/dosen') }}" class="nav-link {{ ($activeMenu == 'peserta-dosen') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Peserta Dosen</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ route('biodata.tendik.index') }}" class="nav-link {{ ($activeMenu == 'peserta-tendik') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Peserta Tendik</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ url('/admin') }}" class="nav-link {{ ($activeMenu == 'admin') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Admin</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @endauth

            <!-- Dropdown DAFTAR KAMPUS -->
            @auth
            @if(Auth::user()->role == 'admin')
            <li class="nav-item mt-1 has-treeview {{ in_array($activeMenu, ['kampus', 'jurusan', 'prodi']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['kampus', 'jurusan', 'prodi']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        KAMPUS
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item ml-2">
                        <a href="{{ url('/kampus') }}" class="nav-link {{ ($activeMenu == 'kampus') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Daftar Kampus</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('/jurusan') }}" class="nav-link {{ ($activeMenu == 'jurusan') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Daftar Jurusan</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('/prodi') }}" class="nav-link {{ ($activeMenu == 'prodi') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Daftar Program Studi</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endauth

            <!-- Dropdown PENDAFTARAN -->
            @auth
            @if(Auth::user()->role == 'admin')
            <li class="nav-item mt-1 has-treeview {{ in_array($activeMenu, ['verifikasi', 'edit-formulir']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['verifikasi', 'edit-formulir']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>
                        PENDAFTARAN
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ url('/verifikasi') }}" class="nav-link {{ ($activeMenu == 'verifikasi') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Verifikasi Pendaftaran</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ url('riwayat') }}" class="nav-link {{ ($activeMenu == 'riwayat-pendaftaran') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Riwayat Pendaftaran</p>
                        </a>
                    </li>
                    @endif
                    @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen', 'tendik']))
                    <li class="nav-item ml-2">
                        <a href="{{ url('/pendaftaran/edit') }}" class="nav-link {{ ($activeMenu == 'edit-formulir') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Formulir</p>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @endauth

            @auth
            @if(Auth::user()->role == 'admin')
            <li class="nav-item mt-1 has-treeview {{ in_array($activeMenu, ['jadwal', 'informasi', 'hasil_ujian']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['jadwal', 'informasi', 'hasil_ujian']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-info-circle"></i>
                    <p>
                        PUSAT INFORMASI
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item ml-2">
                        <a href="{{ url('jadwal') }}" class="nav-link {{ ($activeMenu == 'jadwal') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Jadwal</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('informasi') }}" class="nav-link {{ ($activeMenu == 'informasi') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pengumuman</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('hasil_ujian') }}" class="nav-link {{ ($activeMenu == 'hasil_ujian') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Hasil Ujian</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endauth

            @auth
                @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen', 'tendik']))
                    <li class="nav-item mt-1">
                        <a href="{{ route('datadiri.index') }}" class="nav-link {{ ($activeMenu === 'datadiri') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>Data Diri</p>
                        </a>
                    </li>
                @endif
            @endauth

            @auth
                @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen', 'tendik']))
                    <li class="nav-item mt-1">
                        <a href="{{ route('pendaftaran.index') }}" class="nav-link {{ ($activeMenu === 'pendaftaran') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>Pendaftaran</p>
                        </a>
                    </li>
                @endif
            @endauth

            <div class="text-center">
                <div class="border border-secondary text-white px-3 py-1 rounded-pill d-inline-flex align-items-center gap-2 small">
                    <i class="bi bi-clock"></i> <!-- Bootstrap Icons -->
                    <span id="clock">00:00:00</span>
                </div>
            </div>

        </ul>
    </nav>
</div>