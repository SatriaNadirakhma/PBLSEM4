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
</style>
@endpush

@section('content')
    <div class="card card-outline card-primary shadow-sm">
       <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0 flex-grow-1">{{ $page->title }}</h3>
            <button class="btn btn-primary ms-auto" id="btn-edit">
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
                        <td>{{ $mahasiswa->status }}</td>
                    </tr>
                    <tr>
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

                    <!-- 1. NIM -->
                    <div class="form-group">
                        <label>NIM</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->nim }}" readonly>
                        <div class="readonly-feedback">Kolom NIM bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <!-- 2. NIK -->
                    <div class="form-group">
                        <label>NIK</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->nik }}" readonly>
                        <div class="readonly-feedback">Kolom NIK bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <!-- 3. Nama -->
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->mahasiswa_nama }}" readonly>
                        <div class="readonly-feedback">Kolom nama bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <!-- 4. Angkatan -->
                    <div class="form-group">
                        <label>Angkatan</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->angkatan }}" readonly>
                        <div class="readonly-feedback">Kolom angkatan bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <!-- 5. No. Telepon (EDITABLE) -->
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telp" class="form-control" value="{{ $mahasiswa->no_telp }}">
                    </div>

                    <!-- 6. Alamat Asal (EDITABLE) -->
                    <div class="form-group">
                        <label>Alamat Asal</label>
                        <textarea name="alamat_asal" class="form-control">{{ $mahasiswa->alamat_asal }}</textarea>
                    </div>

                    <!-- 7. Alamat Sekarang (EDITABLE) -->
                    <div class="form-group">
                        <label>Alamat Sekarang</label>
                        <textarea name="alamat_sekarang" class="form-control">{{ $mahasiswa->alamat_sekarang }}</textarea>
                    </div>

                    <!-- 8. Jenis Kelamin -->
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->jenis_kelamin }}" readonly>
                        <div class="readonly-feedback">Kolom jenis kelamin bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <!-- 9. Status -->
                    <div class="form-group">
                        <label>Status</label>
                        <input type="text" class="form-control bg-light text-muted readonly-field" value="{{ $mahasiswa->status }}" readonly>
                        <div class="readonly-feedback">Kolom status bersifat tetap dan tidak dapat diubah oleh pengguna</div>
                    </div>

                    <!-- 10. Program Studi -->
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

            $.ajax({
                url: '{{ route("datadiri.mahasiswa.update") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    alert(res.message);
                    location.reload();
                },
                error: function (xhr) {
                    alert('Terjadi kesalahan');
                }
            });
        });
    });
</script>
@endpush
