@extends('layouts.template')

@section('content')
<div class="container mt-4" style="max-width: 700px;">
    <div class="card">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0" style="font-weight: 600;">Konfirmasi Pendaftaran Berbayar</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Anda sudah pernah diterima dalam program ini. Untuk mendaftar kembali, silakan mengikuti prosedur pendaftaran berbayar.
            </div>

            <div class="border p-3 rounded mb-4">
                <h6 class="fw-bold">Informasi Pendaftaran Terakhir:</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Tanggal Pendaftaran:</strong></p>
                        <p>{{ \Carbon\Carbon::parse($pendaftaran->tanggal_pendaftaran)->format('d F Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Status:</strong></p>
                        <p><span class="badge bg-success">Diterima</span></p>
                    </div>
                </div>
            </div>
             <div class="border p-3 rounded mb-4">
                <h6 class="fw-bold">Informasi Pendaftaran:</h6>
                <ul>
                    <li>Biaya pendaftaran: Rp 500.000</li>
                    <li>Proses verifikasi: 1-2 hari kerja</li>
                    <li>Pembayaran via transfer bank atau virtual account</li>
                </ul>
            </div>

            <div class="border p-3 rounded bg-light">
                <h6 class="fw-bold mb-3">Pendaftaran Berbayar melalui ITC</h6>
                <p>Untuk mengikuti program ini kembali, silakan daftar melalui portal resmi ITC:</p>
                
                <div class="d-grid gap-2">
                    <a href="https://itc-indonesia.com/program-pendaftaran" 
                       class="btn btn-warning" 
                       target="_blank"
                       id="btn-itc">
                        <i class="fas fa-external-link-alt me-2"></i> Daftar via ITC
                    </a>
                </div>

                <div class="mt-3">
                    <p class="small text-muted">
                        <i class="fas fa-clock me-1"></i> Proses pendaftaran akan dibuka di browser baru
                    </p>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Alert informasi saat halaman dimuat
    Swal.fire({
        title: 'Pendaftaran Berbayar',
        html: `Anda sudah terdaftar dalam program ini. Untuk mengikuti kembali, silakan daftar melalui portal ITC.`,
        icon: 'info',
        confirmButtonText: 'Mengerti',
        footer: '<a href="https://itc-indonesia.com" target="_blank">Kunjungi ITC Indonesia</a>'
    });

    // Tracking klik tombol ITC
    $('#btn-itc').on('click', function() {
        // Anda bisa menambahkan tracking AJAX di sini
        console.log('Redirect ke ITC dilakukan');
    });
});
</script>
@endpush

@endsection