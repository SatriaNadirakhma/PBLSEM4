@empty($mahasiswa)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data mahasiswa yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/biodata/mahasiswa') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/biodata/mahasiswa/' . $mahasiswa->mahasiswa_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Mahasiswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>NIM</label>
                    <input value="{{ $mahasiswa->nim }}" type="text" name="nim" id="nim" class="form-control" required>
                    <small id="error-nim" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIK</label>
                    <input value="{{ $mahasiswa->nik }}" type="text" name="nik" id="nik" class="form-control" required>
                    <small id="error-nik" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Mahasiswa</label>
                    <input value="{{ $mahasiswa->mahasiswa_nama }}" type="text" name="mahasiswa_nama" id="mahasiswa_nama" class="form-control" required>
                    <small id="error-mahasiswa_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Angkatan</label>
                    <input value="{{ $mahasiswa->angkatan }}" type="text" name="angkatan" id="angkatan" class="form-control">
                    <small id="error-angkatan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Telp</label>
                    <input value="{{ $mahasiswa->no_telp }}" type="text" name="no_telp" id="no_telp" class="form-control">
                    <small id="error-no_telp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Asal</label>
                    <input value="{{ $mahasiswa->alamat_asal }}" type="text" name="alamat_asal" id="alamat_asal" class="form-control">
                    <small id="error-alamat_asal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Sekarang</label>
                    <input value="{{ $mahasiswa->alamat_sekarang }}" type="text" name="alamat_sekarang" id="alamat_sekarang" class="form-control">
                    <small id="error-alamat_sekarang" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ $mahasiswa->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $mahasiswa->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <small id="error-jenis_kelamin" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="Aktif" {{ $mahasiswa->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Lulus" {{ $mahasiswa->status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                    </select>
                    <small id="error-status" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Keterangan</label>
                    <input value="{{ $mahasiswa->keterangan }}" type="text" name="keterangan" id="keterangan" class="form-control" readonly>
                    <small id="error-keterangan" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Prodi</label>
                    <select name="prodi_id" id="prodi_id" class="form-control" required>
                        <option value="">--Pilih Prodi--</option>
                        @foreach($prodi as $p)
                            <option value="{{ $p->prodi_id }}" {{ $mahasiswa->prodi_id == $p->prodi_id ? 'selected' : '' }}>
                                {{ $p->prodi_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-prodi_id" class="error-text form-text text-danger"></small>
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
    $(document).ready(function () {
        $("#form-edit").validate({
            rules: {
                nim: { required: true, minlength: 2, maxlength: 20, digits: true, },
                nik: { required: true, minlength: 3, maxlength: 20, digits: true, },
                mahasiswa_nama: { required: true, minlength: 3, maxlength: 100 },
                angkatan: { 
                    required: true, 
                    digits: true, 
                    minlength: 4, 
                    maxlength: 4, 
                    min: 2000, 
                    max: (function() {
                        var now = new Date();
                        var year = now.getFullYear();
                        // Jika bulan >= Agustus (7, karena 0-based), naikkan tahun
                        if (now.getMonth() >= 7) {
                            year += 1;
                        }
                        return year;
                    })()
                },
                no_telp: { required: false, digits: true, minlength: 10, maxlength: 15 },
                jenis_kelamin: { required: true },
                prodi_id: { required: true }
            },
                messages: {
                angkatan: {
                    min: "Tahun angkatan tidak valid, Tahun angkatan harus 2000 ke atas"
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize() + '&_method=PUT',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                confirmButtonText: 'OKE'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'AJAX Error',
                            text: error
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endempty
