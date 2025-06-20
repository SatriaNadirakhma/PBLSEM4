@empty($informasi)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data informasi tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/informasi/' . $informasi->informasi_id . '/update_ajax') }}" method="POST" id="form-edit-informasi">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Informasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control"
                        value="{{ $informasi->judul }}" required minlength="3" maxlength="255">
                    <small id="error-judul" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Isi</label>
                    <textarea name="isi" id="isi" class="form-control" rows="5" required minlength="5">{{ $informasi->isi }}</textarea>
                    <small id="error-isi" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-edit-informasi").validate({
        rules: {
            judul: { required: true, minlength: 3, maxlength: 255 },
            isi: { required: true, minlength: 5 }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize() + '&_method=PUT',
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan AJAX', 'error');
                }
            });
            return false;
        }
    });
});
</script>
@endempty