<form action="{{ url('/biodata/dosen/store_ajax') }}" method="POST" id="form-tambah-dosen">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>NIDN</label>
                    <input type="text" name="nidn" id="nidn" class="form-control" required>
                    <small id="error-nidn" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" id="nik" class="form-control" required>
                    <small id="error-nik" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Dosen</label>
                    <input type="text" name="dosen_nama" id="dosen_nama" class="form-control" required>
                    <small id="error-dosen_nama" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Telp</label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control">
                    <small id="error-no_telp" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Asal</label>
                    <input type="text" name="alamat_asal" id="alamat_asal" class="form-control">
                    <small id="error-alamat_asal" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Sekarang</label>
                    <input type="text" name="alamat_sekarang" id="alamat_sekarang" class="form-control">
                    <small id="error-alamat_sekarang" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                    <small id="error-jenis_kelamin" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <select name="jurusan_id" id="jurusan_id" class="form-control" required>
                        <option value="">Pilih</option>
                        @foreach($jurusan as $item)
                            <option value="{{ $item->id }}">{{ $item->jurusan_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-jurusan_id" class="error-text text-danger"></small>
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
    $("#form-tambah-dosen").validate({
        rules: {
            nidn: { required: true, minlength: 3, maxlength: 20, digits: true, },
            nik: { required: true, minlength: 3, maxlength: 20, digits: true, },
            dosen_nama: { required: true, minlength: 3, maxlength: 100 },
            no_telp: { required: false, digits: true, minlength: 10, maxlength: 15 },
            jenis_kelamin: { required: true },
            jurusan_id: { required: true }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });

                        if (typeof dataDosen !== 'undefined') {
                            dataDosen.ajax.reload();
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(Array.isArray(val) ? val[0] : val);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message || 'Gagal menyimpan data'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengirim data. Silakan coba lagi.'
                    });
                }
            });
            return false;
        }
    });
});
</script>
