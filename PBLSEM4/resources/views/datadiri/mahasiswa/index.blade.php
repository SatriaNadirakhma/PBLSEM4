@extends('layouts.template')

@push('css')
<style>
    .readonly-alert {
        border: 1px solid #dc3545 !important;
    }
    .readonly-feedback {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
        display: none;
    }
    /* Added styles for validation feedback */
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
        display: none; /* Initially hidden */
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>
@endpush

@section('content')
    <div class="card card-outline card-primary shadow-sm">
       <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0 flex-grow-1">{{ $page->title }}</h3>
            <button class="btn btn-warning btn-sm rounded-pill shadow-sm me-2 px-4 py-2" id="btn-edit" style="font-size: 0.95rem;">
                <i class="fas fa-edit"></i> Edit Data Diri
            </button>
        </div>

        <div class="card-body">
             <table class="table table-bordered table-striped table-hover w-100 bg-light">
                <tbody>
                    <tr>
                        <th style="width: 25%;">NIM</th>
                        <td>{{ $mahasiswa->nim }}</td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>{{ $mahasiswa->nik }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $mahasiswa->mahasiswa_nama }}</td>
                    </tr>
                    <tr>
                        <th>Angkatan</th>
                        <td>{{ $mahasiswa->angkatan }}</td>
                    </tr>
                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $mahasiswa->no_telp }}</td>
                    </tr>
                    <tr>
                        <th>Alamat Asal</th>
                        <td class="text-wrap" style="max-width: 400px;">{{ $mahasiswa->alamat_asal }}</td>
                    </tr>
                    <tr>
                        <th>Alamat Sekarang</th>
                        <td class="text-wrap" style="max-width: 400px;">{{ $mahasiswa->alamat_sekarang }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $mahasiswa->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($mahasiswa->status == 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else($mahasiswa->status == 'alumni')
                                <span class="badge badge-danger">Alumni</span>
                            @endif
                        </td>
                    </tr>
                        <th>Program Studi</th>
                        <td>{{ $mahasiswa->prodi->prodi_nama ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="form-edit">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Data Diri</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <div>
                            Anda hanya bisa mengubah <strong>No. Telepon</strong>, <strong>Alamat Asal</strong>, dan <strong>Alamat Sekarang</strong>.
                            Untuk mengubah data lainnya harap menghubungi admin.
                        </div>
                    </div>

                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->nim }}" readonly>
                        <div class="readonly-feedback">Kolom NIM bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->nik }}" readonly>
                        <div class="readonly-feedback">Kolom NIK bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->mahasiswa_nama }}" readonly>
                        <div class="readonly-feedback">Kolom nama bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->angkatan }}" readonly>
                        <div class="readonly-feedback">Kolom angkatan bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <div class="form-group">
                        <label for="no_telp">No. Telepon</label>
                        <input type="text" name="no_telp" id="no_telp" class="form-control" value="{{ $mahasiswa->no_telp }}" pattern="[0-9]*" title="Hanya angka yang diperbolehkan" data-minlength="10" data-maxlength="13">
                        <div class="invalid-feedback" id="no_telp-error"></div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Asal</label>
                        <textarea name="alamat_asal" class="form-control">{{ $mahasiswa->alamat_asal }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Alamat Sekarang</label>
                        <textarea name="alamat_sekarang" class="form-control">{{ $mahasiswa->alamat_sekarang }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->jenis_kelamin }}" readonly>
                        <div class="readonly-feedback">Kolom jenis kelamin bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->status }}" readonly>
                        <div class="readonly-feedback">Kolom status bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <div class="form-group">
                        <label>Program Studi</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->prodi->prodi_nama ?? '-' }}" readonly>
                        <div class="readonly-feedback">Kolom program studi bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function () {
        $('#btn-edit').on('click', function () {
            $('#editModal').modal('show');
            // Clear any previous validation errors when opening the modal
            $('#no_telp').removeClass('is-invalid');
            $('#no_telp-error').hide().text('');
        });

        $('.readonly-field').on('focus click', function () {
            let $input = $(this);
            $input.addClass('readonly-alert');
            $input.siblings('.readonly-feedback').fadeIn();
            setTimeout(function () {
                $input.removeClass('readonly-alert');
                $input.siblings('.readonly-feedback').fadeOut();
            }, 2000);
        });

        $('#form-edit').on('submit', function (e) {
            e.preventDefault();

            let noTelpInput = $('#no_telp');
            let noTelp = noTelpInput.val();
            let noTelpError = $('#no_telp-error');
            let minLength = noTelpInput.data('minlength'); // Get minlength from data attribute
            let maxLength = noTelpInput.data('maxlength'); // Get maxlength from data attribute

            // Clear previous errors
            noTelpInput.removeClass('is-invalid');
            noTelpError.hide().text('');

            // Validate phone number
            if (!/^[0-9]+$/.test(noTelp)) {
                noTelpInput.addClass('is-invalid');
                noTelpError.text('Nomor telepon hanya boleh mengandung angka.').show();
                return; // Stop form submission
            }

            if (noTelp.length < minLength || noTelp.length > maxLength) {
                noTelpInput.addClass('is-invalid');
                noTelpError.text(`Nomor telepon harus antara ${minLength} dan ${maxLength} digit.`).show();
                return; // Stop form submission
            }

            Swal.fire({
                title: 'Simpan perubahan?',
                text: "Pastikan data yang diubah sudah benar",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("datadiri.mahasiswa.update") }}', // Make sure this route is correct for mahasiswa
                        method: 'POST',
                        data: $('#form-edit').serialize(),
                        success: function (res) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: res.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat menyimpan data.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });
    });
</script>
@endpush