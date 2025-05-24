<form action="{{ url('/jadwal/ajax') }}" method="POST" id="form-tambah-jadwal">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Tanggal Pelaksanaan</label>
                    <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" class="form-control" required>
                    <small id="error-tanggal_pelaksanaan" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                    <small id="error-jam_mulai" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required></textarea>
                    <small id="error-keterangan" class="error-text text-danger"></small>
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
    $("#form-tambah-jadwal").validate({
        rules: {
            tanggal_pelaksanaan: { required: true, date: true },
            jam_mulai: { required: true },
            keterangan: { required: true, minlength: 3, maxlength: 255 }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire('Berhasil', response.message, 'success');
                        if (typeof dataJadwal !== 'undefined') {
                            dataJadwal.ajax.reload();
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    } else {
                        $('.error-text').text('');
                        if(response.msgField) {
                            $.each(response.msgField, function(key, val) {
                                $('#error-' + key).text(Array.isArray(val) ? val[0] : val);
                            });
                        }
                        Swal.fire('Gagal', response.message || 'Gagal menyimpan data', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengirim data.', 'error');
                }
            });
            return false;
        }
    });
});
</script>
