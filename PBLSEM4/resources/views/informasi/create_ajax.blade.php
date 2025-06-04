<form action="{{ url('/informasi/ajax') }}" method="POST" id="form-tambah-informasi">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Informasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Judul</label>
                    <input type="text" name="judul" id="judul" class="form-control" required minlength="3" maxlength="255">
                    <small id="error-judul" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Isi</label>
                    <textarea name="isi" id="isi" class="form-control" rows="5" required minlength="5"></textarea>
                    <small id="error-isi" class="error-text text-danger"></small>
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
    $("#form-tambah-informasi").validate({
        rules: {
            judul: { required: true, minlength: 3, maxlength: 255 },
            isi: { required: true, minlength: 5 }
        },
        submitHandler: function(form) {
            // Penting: Ini memaksa TinyMCE untuk menulis kontennya ke textarea asli
            // sebelum form diserialisasi dan dikirim via AJAX.
            if (tinymce.get('isi')) { // Pastikan TinyMCE sudah diinisialisasi
                tinymce.triggerSave();
            }

            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire('Berhasil', response.message, 'success');
                        if (typeof dataInformasi !== 'undefined') {
                            dataInformasi.ajax.reload();
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

    // Hapus event listener hidden.bs.modal dari sini, karena sudah dipindahkan ke index.blade.php
    // $('#modal-master').on('hidden.bs.modal', function () { ... });
});
</script>