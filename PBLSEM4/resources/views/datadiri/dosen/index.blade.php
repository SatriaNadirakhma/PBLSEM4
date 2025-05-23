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
                    <tr><th>NIDN</th><td>{{ $dosen->nidn }}</td></tr>
                    <tr><th>NIK</th><td>{{ $dosen->nik }}</td></tr>
                    <tr><th>Nama</th><td>{{ $dosen->dosen_nama }}</td></tr>
                    <tr><th>No. Telepon</th><td>{{ $dosen->no_telp }}</td></tr>
                    <tr><th>Alamat Asal</th><td class="text-wrap">{{ $dosen->alamat_asal }}</td></tr>
                    <tr><th>Alamat Sekarang</th><td class="text-wrap">{{ $dosen->alamat_sekarang }}</td></tr>
                    <tr><th>Jenis Kelamin</th><td>{{ $dosen->jenis_kelamin }}</td></tr>
                    <tr><th>Jurusan</th><td>{{ $dosen->jurusan->jurusan_nama ?? '-' }}</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal" tabindex="-1">
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
                            @php
                                $readonly = 'class= "form-control bg-light text-muted readonly-field" readonly';
                            @endphp

                            <div class="form-group">
                                <label>NIDN</label>
                                <input type="text" value="{{ $dosen->nidn }}" {!! $readonly !!}>
                                <div class="readonly-feedback">Kolom NIDN tidak dapat diubah</div>
                            </div>

                            <div class="form-group">
                                <label>NIK</label>
                                <input type="text" value="{{ $dosen->nik }}" {!! $readonly !!}>
                                <div class="readonly-feedback">Kolom NIK tidak dapat diubah</div>
                            </div>

                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" value="{{ $dosen->dosen_nama }}" {!! $readonly !!}>
                                <div class="readonly-feedback">Kolom nama tidak dapat diubah</div>
                            </div>

                            <div class="form-group">
                                <label>No. Telepon</label>
                                <input type="text" name="no_telp" class="form-control" value="{{ $dosen->no_telp }}">
                            </div>

                            <div class="form-group">
                                <label>Alamat Asal</label>
                                <textarea name="alamat_asal" class="form-control">{{ $dosen->alamat_asal }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Alamat Sekarang</label>
                                <textarea name="alamat_sekarang" class="form-control">{{ $dosen->alamat_sekarang }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <input type="text" value="{{ $dosen->jenis_kelamin }}" {!! $readonly !!}>
                                <div class="readonly-feedback">Kolom ini tidak dapat diubah</div>
                            </div>

                            <div class="form-group">
                                <label>Jurusan</label>
                                <input type="text" value="{{ $dosen->jurusan->jurusan_nama ?? '-' }}" {!! $readonly !!}>
                                <div class="readonly-feedback">Kolom ini tidak dapat diubah</div>
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
    </div>
@endsection

@push('js')
<script>
    $(function () {
        $('#btn-edit').click(function () {
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

        $('#form-edit').submit(function (e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route("datadiri.dosen.update") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    alert(res.message);
                    location.reload();
                },
                error: function () {
                    alert('Terjadi kesalahan');
                }
            });
        });
    });
</script>
@endpush
