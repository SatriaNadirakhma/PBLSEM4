@empty($mahasiswa)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data mahasiswa yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/mahasiswa') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('biodata/mahasiswa/' . $mahasiswa->mahasiswa_id . '/delete_ajax') }}" method="POST" id="form-delete-mahasiswa">

        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                        Apakah Anda yakin ingin menghapus data mahasiswa berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">NIM:</th>
                            <td class="col-9">{{ $mahasiswa->nim }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">NIK:</th>
                            <td class="col-9">{{ $mahasiswa->nik }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Mahasiswa:</th>
                            <td class="col-9">{{ $mahasiswa->mahasiswa_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Angkatan:</th>
                            <td class="col-9">{{ $mahasiswa->angkatan }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jenis Kelamin:</th>
                            <td class="col-9">{{ $mahasiswa->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Status:</th>
                            <td class="col-9">{{ $mahasiswa->status }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Keterangan:</th>
                            <td class="col-9">{{ $mahasiswa->keterangan }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Program Studi:</th>
                            <td class="col-9">
                                {{ optional($prodi->where('prodi_id', $mahasiswa->prodi_id)->first())->prodi_nama ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        $(document).ready(function () {
            $("#form-delete-mahasiswa").validate({
                rules: {},
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: 'POST',
                        data: $(form).serialize() + '&_method=DELETE',
                        success: function (response) {
                            if (response.status) {
                                $('#modal-master').modal('hide');

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });

                                if (typeof dataMahasiswa !== 'undefined') {
                                    dataMahasiswa.ajax.reload(null, true);
                                } else {
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1500);
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: response.message
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Terjadi kesalahan saat menghapus data.'
                            });
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
