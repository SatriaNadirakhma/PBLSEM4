<form action="{{ route('biodata.mahasiswa.store_ajax') }}" method="POST" id="form-tambah-mahasiswa">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>NIM</label>
                    <input type="text" name="nim" id="nim" class="form-control" required>
                    <small id="error-nim" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" id="nik" class="form-control" required>
                    <small id="error-nik" class="error-text text-danger"></small>
                </div>
                
                
                <div class="form-group">
                    <label>Nama Mahasiswa</label>
                    <input type="text" name="mahasiswa_nama" id="mahasiswa_nama" class="form-control" required>
                    <small id="error-mahasiswa_nama" class="error-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Angkatan</label>
                    <input type="text" name="angkatan" id="angkatan" class="form-control" required>
                    <small id="error-angkatan" class="error-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>No Telp</label>
                    <input type="text" name="no_telp" id="no_telp" class="form-control">
                    <small id="error-no_telp" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Alamat Asal</label>
                    <textarea name="alamat_asal" id="alamat_asal" class="form-control" rows="3"></textarea>
                    <small id="error-alamat_asal" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Alamat Sekarang</label>
                    <textarea name="alamat_sekarang" id="alamat_sekarang" class="form-control" rows="3"></textarea>
                    <small id="error-alamat_sekarang" class="error-text text-danger"></small>
                </div>
                
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">- Pilih Jenis Kelamin -</option>
                        @foreach($jenisKelaminEnum as $jk)
                            <option value="{{ $jk }}">{{ $jk }}</option>
                        @endforeach
                    </select>
                    <small id="error-jenis_kelamin" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">- Pilih Status -</option>
                        @foreach($statusEnum as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <small id="error-status" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <input type="text" name="keterangan" id="keterangan" class="form-control" value="gratis" readonly>
                    <small id="error-keterangan" class="error-text text-danger"></small>
                    <small class="text-info">Status keterangan otomatis diset sebagai "Gratis"</small>
                </div>
                
                <div class="form-group">
                    <label>Program Studi</label>
                    <select name="prodi_id" id="prodi_id" class="form-control" required>
                        <option value="">- Pilih Program Studi -</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->prodi_id }}">{{ $p->prodi_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-prodi_id" class="error-text text-danger"></small>
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
    $("#form-tambah-mahasiswa").validate({
        rules: {
            nim: { required: true, minlength: 10, maxlength: 10, digits: true, },
            nik: { required: true, minlength: 16, maxlength: 16, digits: true, },
            mahasiswa_nama: { required: true, minlength: 3, maxlength: 100 },
            angkatan: { required: true, digits: true, minlength: 4, maxlength: 4, min: 2000},
            no_telp: { required: false, digits: true, minlength: 10, maxlength: 15 },
            alamat_asal: { required: false, maxlength: 255 },
            alamat_sekarang: { required: false, maxlength: 255 },
            jenis_kelamin: { required: true },
            status: { required: true },
            keterangan: { required: false }, // Tidak wajib diisi
            prodi_id: { required: true }
        },
        messages: {
            angkatan: {
                min: "Tahun angkatan tidak valid, Tahun angkatan harus 2000 ke atas"
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
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });

                        if (typeof dataMahasiswa !== 'undefined') {
                            dataMahasiswa.ajax.reload();
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
                error: function(xhr, status, error) {
                    console.log('Error:', xhr.responseText);
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