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
        // Rules tetap sama
        rules: {
            admin_nama: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            no_telp: {
                required: true,
                minlength: 3,
                maxlength: 15
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json', // Tambahkan ini untuk memastikan respons diparsing sebagai JSON
                success: function(response) {
                    console.log('Response:', response); // Tambahkan log untuk debugging
                    
                    if (response.status) {
                        // Tutup modal dengan benar - pastikan selector sesuai dengan struktur HTML Anda
                        $('#modal-tambah-admin').modal('hide'); // Sesuaikan dengan ID modal sebenarnya
                        
                        // Tampilkan pesan sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        
                        // Pastikan variabel dataAdmin sudah didefinisikan sebelumnya
                        if (typeof dataAdmin !== 'undefined') {
                            dataAdmin.ajax.reload();
                        } else {
                            // Jika tidak ada datatables, refresh halaman
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    } else {
                        // Reset pesan error sebelum menampilkan yang baru
                        $('.error-text').text('');
                        
                        // Tampilkan pesan error validasi
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
                    console.error('Ajax Error:', error);
                    console.log('Response Text:', xhr.responseText);
                    
                    // Tampilkan pesan error
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengirim data. Silakan coba lagi.'
                    });
                }
            });
            return false;
        },
        // Error handling tetap sama
    });
});
</script>
