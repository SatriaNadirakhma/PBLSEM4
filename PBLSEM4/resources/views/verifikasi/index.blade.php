@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools d-flex align-items-center">
            {{-- Wrapper for registration toggle status --}}
            <div class="p-2 border border-secondary rounded d-flex align-items-center"> {{-- Added p-2, border, border-secondary, rounded, and d-flex --}}
                <span id="registration-status-text" class="me-2 fw-bold text-{{ $registrationStatus === 'open' ? 'success' : 'danger' }}">
                    Pendaftaran Sedang {{ $registrationStatus === 'open' ? 'Terbuka' : 'Tertutup' }}
                </span>
                {{-- Toggle switch for registration --}}
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success ms-2"> {{-- Added ms-2 for margin-left --}}
                    <input type="checkbox" class="custom-control-input" id="toggle-registration-switch" {{ $registrationStatus === 'open' ? 'checked' : '' }}>
                    <label class="custom-control-label" for="toggle-registration-switch"></label>
                </div>
            </div>

            {{-- Tombol Verify All --}}
            <button type="button" class="btn btn-success btn-sm ml-2" onclick="verifyAll()">
                <i class="fas fa-check-double"></i> Verify All
            </button>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_verifikasi">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>NIM</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Prodi</th>
                        <th>Jurusan</th>
                        <th>Kampus</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
    </div>
</div>
@endsection

@push('js')
<script>
// Fungsi untuk menyimpan status pengiriman WA ke Local Storage
function saveWaDeliveryStatus(pendaftaranId, status) {
    let savedStatuses = JSON.parse(localStorage.getItem('whatsappDeliveryStatuses') || '{}');
    savedStatuses[pendaftaranId] = status;
    localStorage.setItem('whatsappDeliveryStatuses', JSON.stringify(savedStatuses));
}

function modalAction(url = '') {
    console.log("Memuat URL: ", url); // debug
    $('#myModal').load(url, function () {
        let modal = new bootstrap.Modal(document.getElementById('myModal'));
        modal.show();
    });
}

$(document).ready(function () {
    let table = $('#table_verifikasi').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('verifikasi.list') }}",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', className: "text-center", orderable: false, searchable: false },
            { data: 'nim', name: 'nim' },
            { data: 'nik', name: 'nik' },
            { data: 'nama', name: 'nama' },
            { data: 'prodi', name: 'prodi' },
            { data: 'jurusan', name: 'jurusan' },
            { data: 'kampus', name: 'kampus' },
            { data: 'status', name: 'status', className: 'text-center' },
            { data: 'aksi', name: 'aksi', className: 'text-center', orderable: false, searchable: false },
        ]
    });

    // Event listener for the custom switch
    $('#toggle-registration-switch').on('change', function() {
        toggleRegistrationStatus($(this).is(':checked'));
    });
});

function updateStatus(id, status) {
    Swal.fire({
        title: 'Yakin ingin mengubah status?',
        text: "Perubahan ini akan disimpan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Iya',
        cancelButtonText: 'Tidak',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-success me-5',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Tuliskan Catatan untuk Peserta',
                input: 'text',
                inputPlaceholder: 'opsional...',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-success me-2',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((inputResult) => {
                if (inputResult.isConfirmed) {
                    let catatan = inputResult.value;

                    fetch(`/verifikasi/${id}/update`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ status, catatan })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Jika ada status WA dari backend, simpan ke Local Storage
                            if (data.pendaftaran_id && data.pengiriman_status_wa) {
                                saveWaDeliveryStatus(data.pendaftaran_id, data.pengiriman_status_wa);
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: data.message,
                                confirmButtonText: 'OKE',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                location.reload(); // Reload halaman untuk memuat ulang DataTable
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Gagal mengubah status.',
                                confirmButtonText: 'Coba Lagi',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses permintaan.',
                            confirmButtonText: 'OKE',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    });
                }
            });

        }
    });
}

function toggleRegistrationStatus(isChecked) {
    let newStatus = isChecked ? 'open' : 'closed';
    let confirmationText = newStatus === 'open' ? 'Anda yakin ingin membuka pendaftaran?' : 'Anda yakin ingin menutup pendaftaran?';
    let switchElement = $('#toggle-registration-switch');
    let statusTextElement = $('#registration-status-text');

    Swal.fire({
        title: 'Konfirmasi',
        text: confirmationText,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        customClass: {
            confirmButton: 'btn btn-primary me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('verifikasi.toggleRegistrationStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    status: newStatus
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            confirmButtonText: 'OKE',
                            customClass: {
                                confirmButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        }).then(() => {
                            // Update text and color next to the switch
                            if (response.newStatus === 'open') {
                                statusTextElement.text('Pendaftaran Sedang Terbuka').removeClass('text-danger').addClass('text-success');
                                switchElement.prop('checked', true); // Ensure switch is on
                            } else {
                                statusTextElement.text('Pendaftaran Sedang Tertutup').removeClass('text-success').addClass('text-danger');
                                switchElement.prop('checked', false); // Ensure switch is off
                            }
                        });
                    } else {
                        // If AJAX call fails, revert the switch state
                        switchElement.prop('checked', !isChecked);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Gagal mengubah status pendaftaran.',
                            confirmButtonText: 'Coba Lagi',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    }
                },
                error: function(xhr) {
                    // If AJAX call fails, revert the switch state
                    switchElement.prop('checked', !isChecked);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat berkomunikasi dengan server.',
                        confirmButtonText: 'Oke',
                        customClass: {
                            confirmButton: 'btn btn-danger'
                        },
                        buttonsStyling: false
                    });
                }
            });
        } else {
            // If user cancels, revert the switch state to its original position
            switchElement.prop('checked', !isChecked);
        }
    });
}

function verifyAll() {
    Swal.fire({
        title: 'Verify All Pending Status?',
        text: "Semua status 'menunggu' akan diubah menjadi 'diterima'!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Verify All',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        customClass: {
            confirmButton: 'btn btn-success me-5',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Catatan untuk Semua Peserta',
                input: 'text',
                inputPlaceholder: 'Catatan umum (opsional)...',
                showCancelButton: true,
                confirmButtonText: 'Verify All',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-success me-2',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            }).then((inputResult) => {
                if (inputResult.isConfirmed) {
                    let catatan = inputResult.value;

                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang memverifikasi semua data',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch('/verifikasi/verify-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ catatan: catatan })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Jika ada status WA dari backend (untuk verifyAll), simpan ke Local Storage
                            if (data.wa_statuses && Array.isArray(data.wa_statuses)) {
                                data.wa_statuses.forEach(wa_status => {
                                    saveWaDeliveryStatus(wa_status.pendaftaran_id, wa_status.pengiriman_status);
                                });
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: `${data.count} data berhasil diverifikasi`,
                                confirmButtonText: 'OKE',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            }).then(() => {
                                location.reload(); // Reload halaman untuk memuat ulang DataTable
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.message || 'Gagal memverifikasi data.',
                                confirmButtonText: 'Coba Lagi',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memproses permintaan.',
                            confirmButtonText: 'OKE',
                            customClass: {
                                confirmButton: 'btn btn-danger'
                            },
                            buttonsStyling: false
                        });
                    });
                }
            });
        }
    });
}
</script>
@endpush