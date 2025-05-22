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

    <!-- Sidebar Menu -->
    <nav class="mt-2">
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
            <li class="nav-item">
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
            <li class="nav-item has-treeview {{ in_array($activeMenu, ['peserta-mahasiswa', 'peserta-dosen', 'tendik', 'peserta-admin']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['peserta-mahasiswa', 'peserta-dosen', 'tendik', 'admin']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        BIODATA USER
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/biodata/mahasiswa') }}" class="nav-link {{ ($activeMenu == 'peserta-mahasiswa') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Peserta Mahasiswa</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/biodata/dosen') }}" class="nav-link {{ ($activeMenu == 'peserta-dosen') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Peserta Dosen</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('biodata.tendik.index') }}" class="nav-link {{ ($activeMenu == 'peserta-tendik') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Peserta Tendik</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/admin') }}" class="nav-link {{ ($activeMenu == 'admin') ? 'active' : '' }}">
                            <i class="far fa-user nav-icon"></i>
                            <p>Admin</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endauth

            <!-- Dropdown DAFTAR KAMPUS -->
            @auth
            @if(Auth::user()->role == 'admin')
            <li class="nav-item has-treeview {{ in_array($activeMenu, ['kampus', 'jurusan', 'prodi']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['kampus', 'jurusan', 'prodi']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <p>
                        KAMPUS
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/kampus') }}" class="nav-link {{ ($activeMenu == 'kampus') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Daftar Kampus</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/jurusan') }}" class="nav-link {{ ($activeMenu == 'jurusan') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Daftar Jurusan</p>
                        </a>
                    </li>
                    <li class="nav-item">
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
            @if(in_array(Auth::user()->role, ['admin', 'mahasiswa', 'dosen', 'tendik']))
            <li class="nav-item has-treeview {{ in_array($activeMenu, ['verifikasi', 'edit-formulir']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['verifikasi', 'edit-formulir']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>
                        PENDAFTARAN
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/verifikasi') }}" class="nav-link {{ ($activeMenu == 'verifikasi') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Verifikasi Pendaftaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('riwayat') }}" class="nav-link {{ ($activeMenu == 'riwayat-pendaftaran') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Riwayat Pendaftaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/pendaftaran/edit') }}" class="nav-link {{ ($activeMenu == 'edit-formulir') ? 'active' : '' }}">
                            <i class="fa fa-list nav-icon"></i>
                            <p>Edit Formulir</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endauth

            @auth
            @if(in_array(Auth::user()->role, ['admin', 'mahasiswa', 'dosen', 'tendik']))
            <li class="nav-item has-treeview {{ in_array($activeMenu, ['info-jadwal', 'info-pengumuman', 'info-zoom']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['info-jadwal', 'info-pengumuman', 'info-zoom']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-info-circle"></i>
                    <p>
                        PUSAT INFORMASI
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ url('/informasi/jadwal') }}" class="nav-link {{ ($activeMenu == 'info-jadwal') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Jadwal</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/informasi/pengumuman') }}" class="nav-link {{ ($activeMenu == 'info-pengumuman') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pengumuman</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/informasi/zoom') }}" class="nav-link {{ ($activeMenu == 'info-zoom') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Hasil Ujian</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endauth

        </ul>
    </nav>
</div>