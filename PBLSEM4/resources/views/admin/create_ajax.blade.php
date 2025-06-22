<form action="{{ url('/admin/ajax') }}" method="POST" id="form-tambah-admin">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Admin</label>
                    <input value="" type="text" name="admin_nama" id="admin_nama" class="form-control" required>
                    <small id="error-admin_kode" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon Admin</label>
                    <input value="" type="text" name="no_telp" id="no_telp" class="form-control" required>
                    <small id="error-admin_nama" class="error-text text-danger"></small>
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
     $("#form-tambah-admin").validate({
          rules: {
                admin_nama: {
                     required: true,
                     minlength: 3,
                     maxlength: 100
                },
                no_telp: {
                     required: true,
                     minlength: 3,
                     maxlength: 15, 
                     digits: true,
                }
          },
          submitHandler: function(form) {
                $.ajax({
                     url: form.action,
                     type: form.method,
                     data: $(form).serialize(),
                     dataType: 'json',
                     success: function(response) {
                          if (response.status) {
                                $('#modal-tambah-admin').modal('hide');
                                Swal.fire({
                                     icon: 'success',
                                     title: 'Berhasil',
                                     text: response.message
                                });
                                setTimeout(function() {
                                     location.reload();
                                }, 1500);
                          } else {
                                $('.error-text').text('');
                                if (response.msgField) {
                                     $.each(response.msgField, function(prefix, val) {
                                          if (Array.isArray(val)) {
                                                $('#error-' + prefix).text(val[0]);
                                          } else {
                                                $('#error-' + prefix).text(val);
                                          }
                                     });
                                }
                                Swal.fire({
                                     icon: 'error',
                                     title: 'Terjadi Kesalahan',
                                     text: response.message || 'Gagal menyimpan data'
                                });
                          }
                     },
                     error: function(xhr, status, error) {
                          Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal mengirim data. Silakan coba lagi.'
                          });
                     }
                });
                return false;
          },
     });
});
</script>
