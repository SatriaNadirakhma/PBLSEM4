@empty($jadwal)
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
                    Data jadwal yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/jadwal') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/jadwal/' . $jadwal->jadwal_id . '/delete_ajax') }}" method="POST" id="form-delete-jadwal">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                        Apakah Anda yakin ingin menghapus data jadwal berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Tanggal Pelaksanaan:</th>
                            <td class="col-9">{{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Jam Mulai:</th>
                            <td class="col-9">{{ $jadwal->jam_mulai }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Keterangan:</th>
                            <td class="col-9">{{ $jadwal->keterangan }}</td>
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
    // Validasi form delete jadwal
    $("#form-delete-jadwal").validate({
        rules: {},
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: 'POST', // POST karena DELETE disimulasikan
                data: $(form).serialize() + '&_method=DELETE',
                success: function(response) {
                    console.log(response); // Debugging response

                    if (response.status) {
                        // Menutup modal setelah penghapusan berhasil
                        $('#modal-master').modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });

                        // Pastikan dataJadwal sudah didefinisikan sebelum reload
                        if (typeof dataJadwal !== 'undefined') {
                            console.log('Reload DataTables');
                            dataJadwal.ajax.reload(null, true); // Reload DataTables
                        } else {
                            // Jika dataJadwal belum didefinisikan, reload halaman
                            setTimeout(function() {
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
                error: function(xhr) {
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
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endempty
