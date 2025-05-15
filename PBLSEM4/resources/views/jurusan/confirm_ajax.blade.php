@empty($jurusan)
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
                    Data jurusan yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/jurusan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/jurusan/' . $jurusan->jurusan_id . '/delete_ajax') }}" method="POST" id="form-delete-jurusan">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Jurusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                        Apakah Anda yakin ingin menghapus data jurusan berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Kode Jurusan:</th>
                            <td class="col-9">{{ $jurusan->jurusan_kode }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Jurusan:</th>
                            <td class="col-9">{{ $jurusan->jurusan_nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Nama Kampus:</th>
                            <td class="col-9">{{ $jurusan->kampus->kampus_nama ?? '-' }}</td>
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
    $(document).ready(function() {
        $("#form-delete-jurusan").validate({
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize() + '&_method=DELETE',
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: true
                            }).then(() => {
                                $('#modal-master').modal('hide');

                                if (typeof dataJurusan !== 'undefined') {
                                    dataJurusan.ajax.reload(null, true);
                                } else {
                                    location.reload(); // Reload hanya jika user klik OK
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    }
                });
                return false;
            }
        });
    });
    </script>
@endempty
