@extends('layouts.template')

@section('content')
<div class="container mt-4" style="max-width: 700px;">
    <div class="card">
        <div class="card-header bg-primary text-white py-2">
            <h5 class="mb-0" style="font-weight: 600;">Pendaftaran Berbayar untuk {{ ucfirst($role) }}</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Pendaftaran untuk {{ $role }} dilakukan melalui portal ITC dengan biaya registrasi.
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
                <h6 class="fw-bold mb-3">Pendaftaran melalui ITC</h6>
                <p>Silakan klik tombol di bawah ini untuk mengisi formulir pendaftaran:</p>
                
                <div class="d-grid gap-2">
                    <a href="{{ $itc_link }}" 
                       class="btn btn-warning" 
                       target="_blank"
                       id="btn-itc">
                        <i class="fas fa-external-link-alt me-2"></i> Daftar via ITC
                    </a>
                </div>

                <div class="mt-3">
                    <p class="small text-muted">
                        <i class="fas fa-clock me-1"></i> Anda akan diarahkan ke situs ITC Indonesia
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
    // Alert informasi khusus role
    Swal.fire({
        title: 'Pendaftaran {{ ucfirst($role) }}',
        html: `Pendaftaran untuk {{ $role }} dilakukan melalui sistem berbayar ITC.`,
        icon: 'info',
        confirmButtonText: 'Mengerti',
        footer: '<a href="https://itc-indonesia.com" target="_blank">Informasi Biaya dan Syarat</a>'
    });

    // Tracking klik tombol ITC
    $('#btn-itc').on('click', function() {
        // Kirim data ke sistem tracking
        $.ajax({
            url: '/track/itc-redirect',
            method: 'POST',
            data: {
                user_id: {{ auth()->id() }},
                user_role: '{{ $role }}',
                _token: '{{ csrf_token() }}'
            }
        });
    });
});
</script>
@endpush

@endsection