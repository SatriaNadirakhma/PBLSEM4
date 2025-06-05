<div class="modal fade" id="modal-delete-hasil" tabindex="-1" role="dialog" aria-hidden="true">
    @empty($hasil_ujian)
        <div class="modal-dialog modal-lg" role="document" id="modal-master">
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
                        Data hasil ujian yang Anda cari tidak ditemukan.
                    </div>
                    <a href="{{ url('/hasil_ujian') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </div>
    @else
        <form action="{{ url('/hasil_ujian/' . $hasil_ujian->hasil_ujian_id . '/delete_ajax') }}" method="POST" id="form-delete-hasil">
            @csrf
            @method('DELETE')
            <div class="modal-dialog modal-lg" role="document" id="modal-master">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Data Hasil Ujian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                            Apakah Anda yakin ingin menghapus data hasil ujian berikut?
                        </div>
                        <table class="table table-sm table-bordered table-striped">
                            <tr>
                                <th class="text-right col-4">Nilai Listening:</th>
                                <td class="col-8">{{ $hasil_ujian->nilai_listening }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">Nilai Reading:</th>
                                <td>{{ $hasil_ujian->nilai_reading }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">Status Lulus:</th>
                                <td>{{ $hasil_ujian->status_lulus }}</td>
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
            $("#form-delete-hasil").validate({
                submitHandler: function(form) {
                    $.ajax({
                        url: form.action,
                        type: 'POST',
                        data: $(form).serialize(), // _method sudah include
                        success: function(response) {
                            if (response.status) {
                                $('#modal-delete-hasil').modal('hide');
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message
                                });

                                // Reload datatable atau reload page
                                if (typeof dataHasil !== 'undefined') {
                                    dataHasil.ajax.reload(null, false);
                                } else {
                                    setTimeout(() => location.reload(), 1500);
                                }
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
</div>
