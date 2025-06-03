@extends('layouts.template')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0 text-primary">
                            <i class="fas fa-eye me-2"></i>Detail Riwayat Pendaftaran
                        </h4>
                        <a href="{{ route('riwayatPeserta.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Data Mahasiswa -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i> Data Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Nama Lengkap</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa)->mahasiswa_nama ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">NIM</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa)->nim ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">NIK</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa)->nik ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">No. Telepon</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa)->no_telp ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Alamat Asal</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa)->alamat_asal ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Alamat Sekarang</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa)->alamat_sekarang ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Akademik -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap me-2"></i> Data Akademik</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-muted">Program Studi</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa->prodi)->prodi_nama ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-muted">Jurusan</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa->prodi->jurusan)->jurusan_nama ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-muted">Kampus</label>
                            <p class="form-control-plaintext border-bottom">
                                {{ optional($item->mahasiswa->prodi->jurusan->kampus)->kampus_nama ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pendaftaran -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar me-2"></i> Data Pendaftaran</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Kode Pendaftaran</label>
                            <p class="form-control-plaintext border-bottom">
                                <span class="badge bg-light text-dark border fw-bold fs-6">
                                    {{ $item->pendaftaran_kode ?? 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Status</label>
                            <p class="form-control-plaintext border-bottom">
                                @php
                                    $status = optional($item->detail)->status;
                                @endphp
                                @if($status === 'diterima')
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> {{ ucfirst($status) }}
                                    </span>
                                @elseif($status === 'ditolak')
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fas fa-times-circle me-1"></i> {{ ucfirst($status) }}
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="fas fa-clock me-1"></i> {{ ucfirst($status ?? 'Belum ada status') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Tanggal Pendaftaran</label>
                            <p class="form-control-plaintext border-bottom">
                                <i class="fas fa-calendar-plus text-primary me-2"></i>
                                {{ $item->tanggal_pendaftaran ? \Carbon\Carbon::parse($item->tanggal_pendaftaran)->format('d F Y, H:i:s') : 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Tanggal Pelaksanaan</label>
                           <div class="border-bottom py-1">
                            @if(optional($item->jadwal)->tanggal_pelaksanaan && optional($item->jadwal)->jam_mulai)
                                <i class="fas fa-calendar-check text-success me-2"></i>
                                <span class="text-dark">
                                    {{ \Carbon\Carbon::parse($item->jadwal->tanggal_pelaksanaan)->format('d F Y') }},
                                    {{ \Carbon\Carbon::parse($item->jadwal->jam_mulai)->format('H:i:s') }} WIB
                                </span>
                            @elseif(optional($item->jadwal)->tanggal_pelaksanaan)
                                <i class="fas fa-calendar-check text-success me-2"></i>
                                <span class="text-dark">
                                    {{ \Carbon\Carbon::parse($item->jadwal->tanggal_pelaksanaan)->format('d F Y') }}
                                </span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dokumen -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-file-image me-2"></i> Dokumen Pendukung</h5>
                </div>
                <div class="card-body">
                    <!-- Scan KTP -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">Scan KTP</label>
                        @if($item->scan_ktp)
                            <div class="border rounded p-2 text-center">
                                <img src="{{ Storage::url($item->scan_ktp) }}" 
                                     alt="Scan KTP" 
                                     class="img-fluid rounded shadow-sm mb-2"
                                     style="max-height: 200px; cursor: pointer;"
                                     onclick="showImageModal('{{ Storage::url($item->scan_ktp) }}', 'Scan KTP')">
                                <br>
                                <small class="text-muted">Klik untuk memperbesar</small>
                            </div>
                        @else
                            <div class="border rounded p-3 text-center text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">Tidak ada file</p>
                            </div>
                        @endif
                    </div>

                    <!-- Scan KTM -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">Scan KTM</label>
                        @if($item->scan_ktm)
                            <div class="border rounded p-2 text-center">
                                <img src="{{ Storage::url($item->scan_ktm) }}" 
                                     alt="Scan KTM" 
                                     class="img-fluid rounded shadow-sm mb-2"
                                     style="max-height: 200px; cursor: pointer;"
                                     onclick="showImageModal('{{ Storage::url($item->scan_ktm) }}', 'Scan KTM')">
                                <br>
                                <small class="text-muted">Klik untuk memperbesar</small>
                            </div>
                        @else
                            <div class="border rounded p-3 text-center text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">Tidak ada file</p>
                            </div>
                        @endif
                    </div>

                    <!-- Pas Foto -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted">Pas Foto</label>
                        @if($item->pas_foto)
                            <div class="border rounded p-2 text-center">
                                <img src="{{ Storage::url($item->pas_foto) }}" 
                                     alt="Pas Foto" 
                                     class="img-fluid rounded shadow-sm mb-2"
                                     style="max-height: 200px; cursor: pointer;"
                                     onclick="showImageModal('{{ Storage::url($item->pas_foto) }}', 'Pas Foto')">
                                <br>
                                <small class="text-muted">Klik untuk memperbesar</small>
                            </div>
                        @else
                            <div class="border rounded p-3 text-center text-muted">
                                <i class="fas fa-image fa-2x mb-2"></i>
                                <p class="mb-0">Tidak ada file</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
.form-control-plaintext {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 0.5rem;
    margin-bottom: 0;
}
.card {
    border-radius: 12px;
    overflow: hidden;
}
.border-bottom {
    border-bottom: 1px solid #dee2e6 !important;
}
</style>

<!-- JavaScript -->
<script>
function showImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('imageModalLabel').textContent = title;
    var modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}
</script>
@endsection