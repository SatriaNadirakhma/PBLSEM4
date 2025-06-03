@extends('layouts.template')
@section('content')
<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-0 text-primary">
                        <i class="fas fa-history me-2"></i>Riwayat Pendaftaran
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col" class="px-4 py-3"><i class="fas fa-code me-1"></i>Kode</th>
                                    <th scope="col" class="px-4 py-3"><i class="fas fa-user me-1"></i>Nama Mahasiswa</th>
                                    <th scope="col" class="px-4 py-3"><i class="fas fa-id-card me-1"></i>NIM</th>
                                    <th scope="col" class="px-4 py-3"><i class="fas fa-calendar-plus me-1"></i>Tgl Pendaftaran</th>
                                    <th scope="col" class="px-4 py-3"><i class="fas fa-calendar-check me-1"></i>Tgl Pelaksanaan</th>
                                    <th scope="col" class="px-4 py-3 text-center"><i class="fas fa-info-circle me-1"></i>Status</th>
                                    <th scope="col" class="px-4 py-3 text-center"><i class="fas fa-cogs me-1"></i>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($riwayat as $item)
                                    <tr class="align-middle">
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark border fw-bold">
                                                {{ $item->pendaftaran_kode ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-semibold">
                                                        {{ optional($item->mahasiswa)->mahasiswa_nama ?? 'N/A' }}
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="text-muted fw-medium">{{ optional($item->mahasiswa)->nim ?? '-' }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar text-muted me-2"></i>
                                                <span class="text-dark">
                                                    {{ $item->tanggal_pendaftaran ? \Carbon\Carbon::parse($item->tanggal_pendaftaran)->format('d M Y') : '-' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                <span class="text-dark">
                                                    {{ optional($item->jadwal)->tanggal_pelaksanaan ? \Carbon\Carbon::parse($item->jadwal->tanggal_pelaksanaan)->format('d M Y') : '-' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @php
                                                $status = optional($item->detail)->status;
                                            @endphp

                                            @if($status === 'diterima')
                                                <span class="badge bg-success px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i> {{ ucfirst($status) }}
                                                </span>
                                            @elseif($status === 'ditolak')
                                                <span class="badge bg-danger px-3 py-2 rounded-pill">
                                                    <i class="fas fa-times-circle me-1"></i> {{ ucfirst($status) }}
                                                </span>
                                            @elseif($status)
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                                    <i class="fas fa-clock me-1"></i> {{ ucfirst($status) }}
                                                </span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                                    <i class="fas fa-question-circle me-1"></i> Tidak ada status
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('riwayatPeserta.show', $item->pendaftaran_id) }}" 
                                                   class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye me-1"></i> Detail
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                                                <h5 class="text-muted mt-3">Tidak ada data riwayat</h5>
                                                <p class="text-muted">Belum ada riwayat pendaftaran yang tersedia</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if(method_exists($riwayat, 'links'))
                    <div class="card-footer bg-light border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Menampilkan {{ $riwayat->firstItem() ?? 0 }} - {{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() ?? 0 }} data
                            </div>
                            {{ $riwayat->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
}
.table th {
    font-weight: 600;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
    border: none;
}
.table td {
    border: none;
    border-bottom: 1px solid #dee2e6;
}
.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    transition: all 0.2s ease;
}
.card {
    border-radius: 12px;
    overflow: hidden;
}
.badge {
    font-size: 0.75rem;
    font-weight: 500;
}
.btn-outline-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,123,255,0.3);
}
.empty-state {
    padding: 2rem;
}
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    .table th,
    .table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }
    .d-flex.align-items-center .me-3 {
        margin-right: 0.5rem !important;
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }
}
</style>

<!-- Initialize Bootstrap Tooltips -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
