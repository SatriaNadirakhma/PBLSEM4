@empty($jadwal)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data jadwal tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/jadwal/' . $jadwal->jadwal_id . '/update_ajax') }}" method="POST" id="form-edit-jadwal">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" class="form-control" 
                        value="{{ $jadwal->tanggal_pelaksanaan }}" required>
                    <small id="error-tanggal_pelaksanaan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" 
                        value="{{ $jadwal->jam_mulai }}" required>
                    <small id="error-jam_mulai" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required>{{ $jadwal->keterangan }}</textarea>
                    <small id="error-keterangan" class="error-text form-text text-danger"></small>
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
    $("#form-edit-jadwal").validate({
        rules: {
            tanggal_pelaksanaan: { required: true, date: true },
            jam_mulai: { required: true },
            keterangan: { required: true, minlength: 3, maxlength: 255 }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize() + '&_method=PUT',
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
                error: function(xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan AJAX', 'error');
                }
            });
            return false;
        }
    });
});
</script>
@endempty
