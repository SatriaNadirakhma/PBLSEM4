<div class="sidebar">

    <!-- Sidebar Menu -->
    <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
            <li class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link {{ ($activeMenu == 'dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p style="font-weight: 500;">Dashboard</p>
                </a>
            </li>
            <!--DATA USER -->
            @auth
            @if(Auth::user()->role == 'admin')
            <li class="nav-item mt-1">
                <a href="{{ route('user') }}" class="nav-link {{ in_array($activeMenu, ['user']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p style="font-weight: 500;">DATA USER</p>
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
                    <p style="font-weight: 500;">
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
                    <p style="font-weight: 500;">
                        KAMPUS
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item ml-2">
                        <a href="{{ url('/kampus') }}" class="nav-link {{ ($activeMenu == 'kampus') ? 'active' : '' }}">
                            <i class="fas fa-university nav-icon"></i>
                            <p>Daftar Kampus</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('/jurusan') }}" class="nav-link {{ ($activeMenu == 'jurusan') ? 'active' : '' }}">
                            <i class="fas fa-building nav-icon"></i>
                            <p>Daftar Jurusan</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('/prodi') }}" class="nav-link {{ ($activeMenu == 'prodi') ? 'active' : '' }}">
                            <i class="fas fa-book nav-icon"></i>
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
            <li class="nav-item mt-1 has-treeview {{ in_array($activeMenu, ['verifikasi', 'riwayat']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['verifikasi', 'riwayat']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p style="font-weight: 500;">
                        PENDAFTARAN
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ url('/verifikasi') }}" class="nav-link {{ ($activeMenu == 'verifikasi') ? 'active' : '' }}">
                            <i class="fas fa-check-circle nav-icon"></i>
                            <p>Verifikasi Pendaftaran</p>
                        </a>
                    </li>
                    @endif
                    @if(Auth::user()->role == 'admin')
                    <li class="nav-item ml-2">
                        <a href="{{ url('riwayat') }}" class="nav-link {{ ($activeMenu == 'riwayat') ? 'active' : '' }}">
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
            <li class="nav-item mt-1 has-treeview {{ in_array($activeMenu, ['jadwal', 'informasi', 'hasil_ujian', 'KelolaPanduan']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ in_array($activeMenu, ['jadwal', 'informasi', 'hasil_ujian', 'KelolaPanduan']) ? 'active' : '' }}">
                    <i class="nav-icon fas fa-info-circle"></i>
                    <p style="font-weight: 500;">
                        PUSAT INFORMASI
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item ml-2">
                        <a href="{{ url('jadwal') }}" class="nav-link {{ ($activeMenu == 'jadwal') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt nav-icon"></i>
                            <p>Jadwal</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('informasi') }}" class="nav-link {{ ($activeMenu == 'informasi') ? 'active' : '' }}">
                            <i class="fas fa-bullhorn nav-icon"></i>
                            <p>Pengumuman</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ url('hasil_ujian') }}" class="nav-link {{ ($activeMenu == 'hasil_ujian') ? 'active' : '' }}">
                            <i class="fas fa-poll nav-icon"></i>
                            <p>Hasil Ujian</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ route('panduan.admin.index') }}" class="nav-link {{ ($activeMenu ?? '') === 'KelolaPanduan' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-upload"></i>
                            <p>Kelola Panduan</p>
                        </a>
                    </li>
                    <li class="nav-item ml-2">
                        <a href="{{ route('surat.admin.index') }}" class="nav-link {{ ($activeMenu ?? '') === 'KelolaSurat' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i> {{-- Ganti ikon jika perlu --}}
                            <p>Kelola Surat</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endauth


            @auth
                @if(Auth::user()->role == 'admin')
                    <li class="nav-item mt-1">
                        <a href="{{ route('kirimpesan.index') }}" class="nav-link {{ in_array($activeMenu, ['kirimpesan']) ? 'active' : '' }}">
                            <i class="nav-icon fab fa-whatsapp"></i>
                            <p style="font-weight: 500;">KIRIM PESAN</p>
                        </a>
                    </li>
                @endif
            @endauth

            @auth
                @if(Auth::user()->role == 'admin')
                    <li class="nav-item mt-1">
                        <a href="{{ route('kirimemail.index') }}" class="nav-link {{ in_array($activeMenu, ['kirimemail']) ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p style="font-weight: 500;">KIRIM EMAIL</p>
                        </a>
                    </li>
                @endif
            @endauth

            @auth
                @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen', 'tendik']))
                    <li class="nav-item mt-1">
                        <a href="{{ route('datadiri.index') }}" class="nav-link {{ ($activeMenu === 'datadiri') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p style="font-weight: 500;">Data Diri</p>
                        </a>
                    </li>
                @endif
            @endauth

            @auth
                @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen', 'tendik']))
                    <li class="nav-item mt-1">
                        <a href="{{ route('pendaftaran.index') }}" class="nav-link {{ ($activeMenu === 'pendaftaran') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p style="font-weight: 500;">Pendaftaran</p>
                        </a>
                    </li>
                @endif
            @endauth

            @auth
            @if(Auth::user()->role == 'mahasiswa')
            <li class="nav-item mt-1">
                <a href="{{ url('/riwayatPeserta') }}" class="nav-link {{ ($activeMenu == 'riwayatPeserta') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-file-excel"></i>
                    <p style="font-weight: 500;">Riwayat Pendaftaran</p>
                </a>
            </li>
            @endif
            @endauth

            @auth
                @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen', 'tendik']))
                    <li class="nav-item mt-1">
                        <a href="{{ route('hasilPeserta.index') }}" class="nav-link {{ ($activeMenu === 'hasilPeserta') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-poll"></i>
                            <p style="font-weight: 500;">Hasil Ujian</p>
                        </a>
                    </li>
                @endif

                @if(in_array(Auth::user()->role, ['mahasiswa', 'dosen', 'tendik']))
                    <li class="nav-item mt-1">
                        <a href="{{ route('panduan.show') }}" class="nav-link {{ ($activeMenu ?? '') === 'Panduan' ? 'active' : '' }}" target="_blank">
                            <i class="nav-icon fas fa-lightbulb"></i>
                            <p style="font-weight: 500;">Panduan</p>
                        </a>
                    </li>
                    {{-- <li class="nav-item mt-1">
                        <a href="{{ route('surat.user.index') }}" class="nav-link {{ ($activeMenu ?? '') === 'Surat' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope-open-text"></i> 
                            <p style="font-weight: 500;">Surat</p>
                        </a>
                    </li> --}}
                    
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