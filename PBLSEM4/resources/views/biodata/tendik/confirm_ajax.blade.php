@empty($tendik)
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
                Data tendik yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/biodata/tendik') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/biodata/tendik/' . $tendik->tendik_id . '/delete_ajax') }}" method="POST" id="form-delete-tendik">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Tendik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                    Apakah Anda yakin ingin menghapus data tendik berikut?
                </div>
                <table class="table table-sm table-bordered table-striped">
                    <tr>
                        <th class="text-right col-3">NIP:</th>
                        <td class="col-9">{{ $tendik->nip }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">NIK:</th>
                        <td class="col-9">{{ $tendik->nik }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Nama:</th>
                        <td class="col-9">{{ $tendik->tendik_nama }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">No Telepon:</th>
                        <td class="col-9">{{ $tendik->no_telp }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Alamat Asal:</th>
                        <td class="col-9">{{ $tendik->alamat_asal }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Alamat Sekarang:</th>
                        <td class="col-9">{{ $tendik->alamat_sekarang }}</td>
                    </tr>
                    <tr>
                        <th class="text-right col-3">Jenis Kelamin:</th>
                        <td class="col-9">{{ $tendik->jenis_kelamin }}</td>
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
    $("#form-delete-tendik").validate({
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize() + '&_method=DELETE',
                success: function(response) {
                    $('#modal-master').modal('hide');
                    Swal.fire({
                        icon: response.status ? 'success' : 'error',
                        title: response.status ? 'Berhasil' : 'Gagal',
                        text: response.message
                    });

                    if (response.status) {
                        if (typeof dataTendik !== 'undefined') {
                            dataTendik.ajax.reload(null, true);
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
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
