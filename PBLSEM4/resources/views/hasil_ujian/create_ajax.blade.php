<form action="{{ url('/hasil_ujian/ajax') }}" method="POST" id="form-tambah-hasil_ujian">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Hasil Ujian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nilai Listening</label>
                    <input type="number" name="nilai_listening" id="nilai_listening" class="form-control" min="0" max="495" required>
                    <small id="error-nilai_listening" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nilai Reading</label>
                    <input type="number" name="nilai_reading" id="nilai_reading" class="form-control" min="0" max="495" required>
                    <small id="error-nilai_reading" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Status Lulus</label>
                    <select name="status_lulus" id="status_lulus" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Lulus">Lulus</option>
                        <option value="Tidak Lulus">Tidak Lulus</option>
                    </select>
                    <small id="error-status_lulus" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3"></textarea>
                    <small id="error-catatan" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>User ID</label>
                    <input type="number" name="user_id" id="user_id" class="form-control" required>
                    <small id="error-user_id" class="error-text text-danger"></small>
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
    $("#form-tambah-hasil_ujian").validate({
        rules: {
            nilai_listening: { required: true, number: true, min: 0, max: 495 },
            nilai_reading: { required: true, number: true, min: 0, max: 495 },
            status_lulus: { required: true },
            catatan: { maxlength: 255 },
            user_id: { required: true, digits: true }
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
                        if (typeof dataHasilUjian !== 'undefined') {
                            dataHasilUjian.ajax.reload();
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
