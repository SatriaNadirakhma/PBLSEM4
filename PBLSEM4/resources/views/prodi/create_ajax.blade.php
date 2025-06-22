<form action="{{ url('/prodi/ajax') }}" method="POST" id="form-tambah-prodi">
    @csrf
    <div id="modal-tambah-prodi" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Program Studi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Program Studi</label>
                    <input value="" type="text" name="prodi_kode" id="prodi_kode" class="form-control" required>
                    <small id="error-prodi_kode" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Program Studi</label>
                    <input value="" type="text" name="prodi_nama" id="prodi_nama" class="form-control" required>
                    <small id="error-prodi_nama" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>ID Jurusan</label>
                    <select name="jurusan_id" id="jurusan_id" class="form-control" required>
                        <option value="">- Pilih Jurusan -</option>
                        @foreach($jurusan as $l)
                            <option value="{{ $l->jurusan_id }}">{{ $l->jurusan_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-jurusan_id" class="error-text form-text text-danger"></small>
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
    $("#form-tambah-prodi").validate({
        // Rules tetap sama
        rules: {
            prodi_kode: {
                required: true,
                minlength: 3,
                maxlength: 20
            },
            prodi_nama: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            jurusan_id: {
                required: true
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
                        $('#modal-tambah-prodi').modal('hide'); // Sesuaikan dengan ID modal sebenarnya
                        
                        // Tampilkan pesan sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        
                        // Pastikan variabel dataProdi sudah didefinisikan sebelumnya
                        if (typeof dataProdi !== 'undefined') {
                            dataProdi.ajax.reload();
                        } else {
                            // Jika tidak ada datatables, refresh halaman
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    } else {
                        // Reset pesan error sebelum menampilkan yang baru
                        $(form).find('.error-text').text('');
                        
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
