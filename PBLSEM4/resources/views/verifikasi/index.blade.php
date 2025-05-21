@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
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
                        <th>Aksi</th>
                        <th>Status</th>
                        
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
            { data: 'aksi', name: 'aksi', className: 'text-center', orderable: false, searchable: false },
            { data: 'status', name: 'status', className: 'text-center' },
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

</script>
@endpush

