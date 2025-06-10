@extends('layouts.template')

@section('content')
   <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
                <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Riwayat" style="gap: 0.50rem">
                    <button type="button" class="btn btn-sm shadow-sm rounded-pill" 
                        onclick="openExportModal()"
                        style="background-color: #28a745; color: white; font-size: 0.95rem;">
                            <i class="fa fa-file-excel me-1"></i> Ekspor Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Status -->
        <div class="form-horizontal p-2 border-bottom mb-2">
            <div class="row align-items-center">
                <label for="filterStatus" class="col-md-2 col-form-label text-md-end">Filter Status</label>
                <div class="col-md-4">
                    <select id="filterStatus" class="form-control form-control-sm">
                        <option value="">- Semua Status -</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <small class="form-text text-muted">Pilih status pendaftaran</small>
                </div>
            </div>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm" id="table_riwayat">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>NIM</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Export dengan AJAX -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="fas fa-download me-2"></i>Export Data Riwayat Pendaftaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="exportForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="status" class="form-label fw-bold">
                                    <i class="fas fa-filter me-1"></i>Status Pendaftaran
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Semua Status</option>
                                    <option value="diterima">Diterima</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_awal" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-1"></i>Tanggal Awal
                                </label>
                                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Wajib diisi
                                </small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_akhir" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-1"></i>Tanggal Akhir
                                </label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Wajib diisi
                                </small>
                            </div>
                        </div>
                        
                        <!-- Format Export Options -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-file-export me-1"></i>Pilih Format Export
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format_type" id="format_pdf" value="pdf" checked>
                                        <label class="form-check-label" for="format_pdf">
                                            <i class="fas fa-file-pdf text-danger me-1"></i>PDF (dengan gambar)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="format_type" id="format_excel" value="excel">
                                        <label class="form-check-label" for="format_excel">
                                            <i class="fas fa-file-excel text-success me-1"></i>Excel (dengan gambar)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Preview Info -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi Export:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>PDF:</strong> Menampilkan data lengkap dengan gambar KTP, KTM, dan Pas Foto</li>
                                <li><strong>Excel:</strong> Data dalam bentuk tabel dengan thumbnail gambar di setiap cell</li>
                                <li>Tanggal akhir harus lebih besar atau sama dengan tanggal awal</li>
                                <li>Gambar akan dimuat otomatis jika file tersedia di server</li>
                            </ul>
                        </div>
                        
                        <!-- Loading indicator -->
                        <div id="exportLoading" class="text-center d-none">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Sedang memproses export...</p>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Batal
                        </button>
                        <button type="submit" id="exportBtn" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    // Function to open export modal
    function openExportModal() {
        $('#exportModal').modal('show');
    }

    $(document).ready(function () {
        // DataTable initialization
        var dataRiwayat = $('#table_riwayat').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('riwayat/list') }}",
                type: "POST",
                data: function (d) {
                    d.status_filter = $('#filterStatus').val();
                    d._token = '{{ csrf_token() }}';
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nim", className: "text-nowrap" },
                { data: "nik", className: "text-nowrap" },
                { data: "nama", className: "text-nowrap" },
                { data: "tanggal_daftar", className: "text-nowrap text-center" },
                { data: "status", className: "text-center text-capitalize" },
                { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
            ]
        });

        $('#filterStatus').on('change', function () {
            dataRiwayat.ajax.reload();
        });

        // Export Modal AJAX Implementation
        const exportForm = $('#exportForm');
        const tanggalAwal = $('#tanggal_awal');
        const tanggalAkhir = $('#tanggal_akhir');
        const formatPdf = $('#format_pdf');
        const formatExcel = $('#format_excel');
        const exportBtn = $('#exportBtn');
        const exportLoading = $('#exportLoading');
        const statusSelect = $('#status');

        // Update button text and style based on format
        function updateButtonText() {
            if (formatPdf.is(':checked')) {
                exportBtn.removeClass('btn-success').addClass('btn-danger');
                exportBtn.html('<i class="fas fa-file-pdf me-1"></i> Export PDF');
            } else {
                exportBtn.removeClass('btn-danger').addClass('btn-success');
                exportBtn.html('<i class="fas fa-file-excel me-1"></i> Export Excel');
            }
        }

        // Event listeners for format change
        $('input[name="format_type"]').on('change', updateButtonText);

        // Date validation
        tanggalAwal.on('change', function() {
            const awalValue = $(this).val();
            tanggalAkhir.attr('min', awalValue);
            
            if (tanggalAkhir.val() && tanggalAkhir.val() < awalValue) {
                tanggalAkhir.val(awalValue);
                showAlert('Tanggal akhir telah disesuaikan dengan tanggal awal', 'warning');
            }
        });

        tanggalAkhir.on('change', function() {
            if (tanggalAwal.val() && $(this).val() < tanggalAwal.val()) {
                showAlert('Tanggal akhir harus lebih besar atau sama dengan tanggal awal', 'danger');
                $(this).val(tanggalAwal.val());
            }
        });

        // Show alert function
        function showAlert(message, type = 'info') {
            const alertId = 'alert-' + type;
            $('#' + alertId).remove(); // Remove existing alert
            
            const alertDiv = $(`
                <div id="${alertId}" class="alert alert-${type} alert-dismissible fade show mt-2">
                    <i class="fas fa-exclamation-circle me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            
            $('#exportModal .modal-body').prepend(alertDiv);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                alertDiv.fadeOut(() => alertDiv.remove());
            }, 3000);
        }

        // AJAX form submission
        exportForm.on('submit', function(e) {
            e.preventDefault();
            
            // Show loading
            exportLoading.removeClass('d-none');
            exportBtn.prop('disabled', true);
            
            // Get form data
            const tanggalAwalVal = tanggalAwal.val();
            const tanggalAkhirVal = tanggalAkhir.val();
            const statusVal = statusSelect.val();
            const format = $('input[name="format_type"]:checked').val();
            
            // Validation
            if (!tanggalAwalVal || !tanggalAkhirVal) {
                showAlert('Harap isi kedua tanggal terlebih dahulu.', 'warning');
                exportLoading.addClass('d-none');
                exportBtn.prop('disabled', false);
                return;
            }
            
            if (tanggalAkhirVal < tanggalAwalVal) {
                showAlert('Tanggal akhir harus lebih besar atau sama dengan tanggal awal', 'danger');
                exportLoading.addClass('d-none');
                exportBtn.prop('disabled', false);
                return;
            }
            
            // Prepare AJAX request
            const exportUrl = format === 'excel' ? 
                "{{ route('riwayat.export_excel') }}" : 
                "{{ route('riwayat.export_pdf') }}";
            
            $.ajax({
                url: exportUrl,
                type: 'GET',
                data: {
                    status: statusVal,
                    tanggal_awal: tanggalAwalVal,
                    tanggal_akhir: tanggalAkhirVal
                },
                xhrFields: {
                    responseType: 'blob' // Important for file download
                },
                success: function(data, status, xhr) {
                    // Create blob and download link
                    const blob = new Blob([data], { 
                        type: format === 'excel' ? 
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 
                            'application/pdf' 
                    });
                    
                    const url = window.URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    
                    // Set filename
                    const filename = `riwayat_pendaftaran_${tanggalAwalVal}_${tanggalAkhirVal}.${format === 'excel' ? 'xlsx' : 'pdf'}`;
                    link.download = filename;
                    
                    // Trigger download
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    window.URL.revokeObjectURL(url);
                    
                    showAlert(`Export ${format.toUpperCase()} berhasil diunduh!`, 'success');
                    
                    // Hide modal after success
                    setTimeout(() => {
                        $('#exportModal').modal('hide');
                    }, 1500);
                },
                error: function(xhr, status, error) {
                    console.error('Export error:', error);
                    let errorMessage = 'Terjadi kesalahan saat export data.';
                    
                    if (xhr.status === 404) {
                        errorMessage = 'Route export tidak ditemukan.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan server saat memproses export.';
                    } else if (xhr.status === 422) {
                        errorMessage = 'Data yang dikirim tidak valid.';
                    }
                    
                    showAlert(errorMessage, 'danger');
                },
                complete: function() {
                    // Hide loading and enable button
                    exportLoading.addClass('d-none');
                    exportBtn.prop('disabled', false);
                }
            });
        });

        // Reset modal when closed
        $('#exportModal').on('hidden.bs.modal', function() {
            exportForm[0].reset();
            exportLoading.addClass('d-none');
            exportBtn.prop('disabled', false);
            updateButtonText();
            
            // Remove all alerts
            $('.alert-dismissible').remove();
        });

        // Initialize button text
        updateButtonText();
    });
</script>

<style>
#exportModal .modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.form-check {
    padding: 0.5rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    transition: all 0.2s ease-in-out;
}

.form-check:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
}

.form-check-input:checked + .form-check-label {
    color: #0d6efd;
    font-weight: 600;
}

.alert {
    border-left: 4px solid;
}

.alert-info {
    border-left-color: #0dcaf0;
}

.alert-warning {
    border-left-color: #ffc107;
}

.alert-danger {
    border-left-color: #dc3545;
}

.alert-success {
    border-left-color: #198754;
}

#exportLoading {
    padding: 2rem;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>
@endpush