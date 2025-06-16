@extends('layouts.template')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Section -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                    <i class="fas fa-poll text-white fs-4"></i>
                </div>
                <div>
                    <p class="text-muted mb-0" style="margin-left: 16px;">Ringkasan hasil ujian dan pencapaian Anda <br> Silakan tinjau hasil di bawah ini.</p>
                </div>
            </div>

            @if ($hasilPeserta->isEmpty())
                <!-- Empty State -->
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-clipboard-list text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Belum Ada Hasil Ujian</h4>
                    <p class="text-muted mb-4">Anda belum mengikuti ujian apapun. Silakan ikuti ujian terlebih dahulu untuk melihat hasil.</p>
                    <a href="{{ route('pendaftaran.index') }}" class="btn btn-primary">
                        <i class="fas fa-play me-2"></i>Mulai Ujian
                    </a>
                </div>
            @else
                <!-- Statistics Overview -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-primary text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-clipboard-check fs-2 mb-2"></i>
                                <h5 class="card-title">Total Ujian</h5>
                                <h3 class="mb-0">{{ $hasilPeserta->count() }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-success text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fs-2 mb-2"></i>
                                <h5 class="card-title">Nilai Terendah</h5>
                                <h3 class="mb-0">{{ $hasilPeserta->min('nilai_total')  }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-warning text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-star fs-2 mb-2"></i>
                                <h5 class="card-title">Rata-rata</h5>
                                <h3 class="mb-0">{{ number_format($hasilPeserta->avg('nilai_total'), 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card border-0 bg-info text-white h-100">
                            <div class="card-body text-center">
                                <i class="fas fa-trophy fs-2 mb-2"></i>
                                <h5 class="card-title">Nilai Tertinggi</h5>
                                <h3 class="mb-0">{{ $hasilPeserta->max('nilai_total') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results List -->
                <div class="row">
                    @foreach ($hasilPeserta as $hasil)
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 hover-card">
                                <div class="card-header bg-white border-bottom-0 py-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0 text-primary fw-bold">
                                            <i class="fas fa-file-alt me-2"></i>Ujian #{{ $loop->iteration }}</>
                                        </h5>
                                        @if ($hasil->status_lulus == 'lulus')
                                            <span class="badge bg-success fs-6 px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i> {{ $hasil->status_lulus }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6 px-3 py-2">
                                                <i class="fas fa-times-circle me-1"></i> {{ $hasil->status_lulus }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="card-body">
                                    <!-- Progress Bars -->
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-3">Detail Nilai:</h6>
                                        
                                        <!-- Listening Score -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="fw-semibold">Listening</small>
                                                <small class="fw-semibold">{{ $hasil->nilai_listening }}/495</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ $hasil->nilai_listening }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <!-- Reading Score -->
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1">
                                                <small class="fw-semibold">Reading</small>
                                                <small class="fw-semibold">{{ $hasil->nilai_reading }}/495</small>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning" role="progressbar" 
                                                     style="width: {{ $hasil->nilai_reading }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Total Score Card -->
                                    <div class="bg-light rounded-3 p-3 text-center mb-3">
                                        <h6 class="text-muted mb-1">Total Nilai</h6>
                                        <h2 class="mb-0 fw-bold 
                                            @if($hasil->nilai_total >= 800) text-success
                                            @elseif($hasil->nilai_total >= 500) text-warning
                                            @else text-danger @endif">
                                            {{ $hasil->nilai_total }}
                                        </h2>
                                        <small class="text-muted d-block mb-2">dari 990</small>

                                        <!-- Icon Status Lulus / Tidak -->
                                        @if (strtolower($hasil->status_lulus) === 'lulus')
                                            <i class="fas fa-check-circle text-success" style="font-size: 2rem;" title="Lulus"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger" style="font-size: 2rem;" title="Tidak Lulus"></i>
                                        @endif
                                    </div>

                                    <!-- Additional Information -->
                                    <div class="row">
                                        <!-- Jadwal -->
                                        @if($hasil->jadwal && $hasil->jadwal->tanggal_pelaksanaan)
                                            <div class="col-12 mb-3">
                                                <div class="info-card bg-gradient-info">
                                                    <div class="info-icon">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <h6 class="info-title">Tanggal Pelaksanaan</h6>
                                                        <p class="info-text">{{ \Carbon\Carbon::parse($hasil->jadwal->tanggal_pelaksanaan)->format('d F Y') }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Notes -->
                                        @if($hasil->catatan)
                                            <div class="col-12">
                                                <div class="info-card bg-gradient-primary">
                                                    <div class="info-icon">
                                                        <i class="fas fa-sticky-note"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <h6 class="info-title">Catatan</h6>
                                                        <p class="info-text">{{ $hasil->catatan }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Ujian Lagi Card -->
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm h-100 hover-card d-flex align-items-center justify-content-center" style="min-height: 100%;">
                            <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                <div class="mb-3">
                                    <i class="fas fa-plus-circle text-primary" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="card-title mb-2 fw-bold text-primary">Ikuti Ujian Lagi</h5>
                                <p class="text-muted mb-3">Ingin mencoba ujian lagi untuk meningkatkan skor Anda?</p>
                                <a href="{{ route('pendaftaran.index') }}" class="btn btn-primary">
                                    <i class="fas fa-play me-2"></i> Mulai Ujian Baru
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- tombol SURAT KETERANGAN --}}
                    @if (Auth::check() && Auth::user()->role === 'mahasiswa')
                        <div class="col-lg-6 mb-4">
                            <div class="card border-0 shadow-sm h-100 hover-card d-flex align-items-center justify-content-center">
                                <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                    <div class="mb-3">
                                        <i class="fas fa-file-pdf text-danger" style="font-size: 3rem;"></i>
                                    </div>
                                    <h5 class="card-title mb-2 fw-bold text-danger">Surat Keterangan</h5> {{-- Ganti judul card --}}
                                    <p class="text-muted mb-3">Dapatkan surat keterangan khusus bagi Anda yang telah mencoba berkali-kali namun belum berhasil.</p>
                                    
                                    {{-- Tombol akan mengarah ke rute preview --}}
                                    @if ($canAccessSurat && $suratKeterangan)
                                        <a href="{{ route('hasilPeserta.PreviewSuratKeterangan') }}" class="btn btn-danger" target="_blank"> {{-- Ubah rute dan target="_blank" --}}
                                            <i class="fas fa-download me-2"></i> Unduh Surat Keterangan {{-- Ubah teks tombol dan ikon --}}
                                        </a>
                                    @else
                                        {{-- Tombol untuk memicu modal jika kondisi tidak terpenuhi --}}
                                        <button type="button" class="btn btn-secondary disabled-download-btn" data-toggle="modal" data-target="#notQualifiedModal">
                                            <i class="fas fa-download me-2"></i> Unduh Surat Keterangan {{-- Ubah teks tombol dan ikon --}}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="modal fade" id="notQualifiedModal" tabindex="-1" role="dialog" aria-labelledby="notQualifiedModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="notQualifiedModalLabel">Informasi Surat Keterangan</h5> {{-- Ubah judul modal --}}
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <i class="fas fa-info-circle text-info mb-3" style="font-size: 3rem;"></i>
                                    <p>Maaf, Anda belum memenuhi kriteria untuk mengakses surat ini.</p> {{-- Ubah pesan --}}
                                    <p class="text-muted small">Surat keterangan ini hanya tersedia bagi peserta yang telah mengikuti ujian dan tidak lulus sebanyak dua kali atau lebih.</p>
                                    @if (session('error'))
                                        <div class="alert alert-warning mt-3" role="alert">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.progress {
    border-radius: 10px;
    background-color: #f8f9fa;
}

.progress-bar {
    border-radius: 10px;
}

/* Enhanced Info Cards */
.info-card {
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.info-icon {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon i {
    font-size: 16px;
    color: white;
}

.info-content {
    flex: 1;
}

.info-title {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
    opacity: 0.9;
}

.info-text {
    margin: 0;
    font-size: 15px;
    font-weight: 500;
    line-height: 1.4;
}

/* Subtle animation for info cards */
.info-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.info-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

/* Add subtle pattern overlay */
.info-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50% 0 0 50%;
    transform: translate(20px, -20px);
}

@media print {
    .btn, .hover-card:hover {
        display: none !important;
    }
    
    .info-card {
        background: #f8f9fa !important;
        color: #333 !important;
        border: 1px solid #dee2e6 !important;
    }
    
    .info-icon {
        background: #e9ecef !important;
    }
    
    .info-icon i {
        color: #6c757d !important;
    }
}

@media (max-width: 576px) {
    .info-card {
        padding: 12px;
        gap: 10px;
    }
    
    .info-icon {
        width: 35px;
        height: 35px;
    }
    
    .info-icon i {
        font-size: 14px;
    }
    
    .info-title {
        font-size: 13px;
    }
    
    .info-text {
        font-size: 14px;
    }
}
</style>
@endsection