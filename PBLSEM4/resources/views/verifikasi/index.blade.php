@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-success btn-sm" onclick="verifyAll()">
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
            // Tampilkan prompt untuk catatan setelah konfirmasi
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
                                location.reload();
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
                    });
                }
                // Jika klik Batal di input, tidak lakukan apa pun
            });

        }
        // Jika klik "Tidak", tidak terjadi apa-apa
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
            // Tampilkan prompt untuk catatan global
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

                    // Tampilkan loading
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
                                location.reload();
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