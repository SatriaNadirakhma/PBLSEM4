@empty($dosen)
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
                Data yang Anda cari tidak ditemukan.
            </div>
            <a href="{{ url('/biodata/dosen') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/biodata/dosen/' . $dosen->dosen_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Dosen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>NIDN</label>
                    <input value="{{ $dosen->nidn }}" type="text" name="nidn" id="nidn" class="form-control" required>
                    <small id="error-nidn" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIK</label>
                    <input value="{{ $dosen->nik }}" type="text" name="nik" id="nik" class="form-control" required>
                    <small id="error-nik" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Dosen</label>
                    <input value="{{ $dosen->dosen_nama }}" type="text" name="dosen_nama" id="dosen_nama" class="form-control" required>
                    <small id="error-dosen_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Telp</label>
                    <input value="{{ $dosen->no_telp }}" type="text" name="no_telp" id="no_telp" class="form-control">
                    <small id="error-no_telp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Asal</label>
                    <input value="{{ $dosen->alamat_asal }}" type="text" name="alamat_asal" id="alamat_asal" class="form-control">
                    <small id="error-alamat_asal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Sekarang</label>
                    <input value="{{ $dosen->alamat_sekarang }}" type="text" name="alamat_sekarang" id="alamat_sekarang" class="form-control">
                    <small id="error-alamat_sekarang" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ $dosen->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $dosen->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <small id="error-jenis_kelamin" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jurusan</label>
                    <select name="jurusan_id" id="jurusan_id" class="form-control" required>
                        <option value="">--Pilih Jurusan--</option>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->jurusan_id }}" {{ $dosen->jurusan_id == $j->jurusan_id ? 'selected' : '' }}>
                                {{ $j->jurusan_nama }}
                            </option>
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
    $(document).ready(function () {
        $("#form-edit").validate({
            rules: {
                nidn: {
                    required: true,
                    minlength: 10,
                    maxlength: 20, 
                    digits: true,
                },
                nik: {
                    required: true,
                    minlength: 3,
                    maxlength: 20, 
                    digits: true,
                },
                dosen_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                jenis_kelamin: {
                    required: true
                },
                jurusan_id: {
                    required: true
                },
                no_telp: { 
                    required: false, digits: true, minlength: 10, maxlength: 15 
                },
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
