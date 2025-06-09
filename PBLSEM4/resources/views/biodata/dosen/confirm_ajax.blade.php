@empty($dosen)
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
                    Data dosen yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/dosen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/dosen/' . $dosen->dosen_id . '/delete_ajax') }}" method="POST" id="form-delete-dosen">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Dosen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                        Apakah Anda yakin ingin menghapus data dosen berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">NIDN:</th>
                            <td class="col-9">{{ $dosen->nidn }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">NIK:</th>
                            <td class="col-9">{{ $dosen->nik }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Dosen:</th>
                            <td class="col-9">{{ $dosen->dosen_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nomor Telepon:</th>
                            <td class="col-9">{{ $dosen->nidn }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">NIDN:</th>
                            <td class="col-9">{{ $dosen->nidn }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Alamat Asal:</th>
                            <td class="col-9">{{ $dosen->alamat_asal }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Alamat Sekarang:</th>
                            <td class="col-9">{{ $dosen->alamat_sekarang }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jenis Kelamin:</th>
                            <td class="col-9">{{ $dosen->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Asal Jurusan:</th>
                            <td class="col-9">{{ $dosen->jurusan_nama ?? '-' }}</td>
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
            $("#form-delete-dosen").validate({
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

                                if (typeof dataDosen !== 'undefined') {
                                    dataDosen.ajax.reload(null, true);
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
