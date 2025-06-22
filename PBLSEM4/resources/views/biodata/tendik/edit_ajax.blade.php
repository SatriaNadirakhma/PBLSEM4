@empty($tendik)
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
            <a href="{{ url('/biodata/tendik') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else
<form action="{{ url('/biodata/tendik/' . $tendik->tendik_id . '/update_ajax') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Tendik</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>NIP</label>
                    <input value="{{ $tendik->nip }}" type="text" name="nip" id="nip" class="form-control" required>
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIK</label>
                    <input value="{{ $tendik->nik }}" type="text" name="nik" id="nik" class="form-control" required>
                    <small id="error-nik" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Tendik</label>
                    <input value="{{ $tendik->tendik_nama }}" type="text" name="tendik_nama" id="tendik_nama" class="form-control" required>
                    <small id="error-tendik_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>No Telp</label>
                    <input value="{{ $tendik->no_telp }}" type="text" name="no_telp" id="no_telp" class="form-control">
                    <small id="error-no_telp" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Asal</label>
                    <input value="{{ $tendik->alamat_asal }}" type="text" name="alamat_asal" id="alamat_asal" class="form-control">
                    <small id="error-alamat_asal" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Sekarang</label>
                    <input value="{{ $tendik->alamat_sekarang }}" type="text" name="alamat_sekarang" id="alamat_sekarang" class="form-control">
                    <small id="error-alamat_sekarang" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ $tendik->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $tendik->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <small id="error-jenis_kelamin" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Kampus</label>
                    <select name="kampus_id" id="kampus_id" class="form-control" required>
                        <option value="">-- Pilih Kampus --</option>
                        @foreach($kampus as $k)
                            <option value="{{ $k->kampus_id }}" {{ $tendik->kampus_id == $k->kampus_id ? 'selected' : '' }}>
                                {{ $k->kampus_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-kampus_id" class="error-text form-text text-danger"></small>
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
                nip: {
                    required: true,
                    minlength: 18, // Changed from 2 to 18 based on controller
                    maxlength: 20,
                    digits: true,
                },
                nik: {
                    required: true,
                    minlength: 16, // Changed from 3 to 16 based on controller
                    maxlength: 16, // Changed from 20 to 16 based on controller
                    digits: true,
                },
                tendik_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                },
                jenis_kelamin: {
                    required: true
                },
                kampus_id: {
                    required: true
                },
                no_telp: { // Corrected comma placement and added back
                    required: false,
                    digits: true,
                    minlength: 11, // Changed from 10 to 11 based on controller
                    maxlength: 13 // Changed from 15 to 13 based on controller
                },
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: 'POST', // Keep as POST if using _method=PUT, or change to 'PUT'
                    data: $(form).serialize() + '&_method=PUT', // This is good for sending PUT via POST
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
                            text: error + ' (Status: ' + status + ', XHR ReadyState: ' + xhr.readyState + ', XHR Status: ' + xhr.status + ')'
                        });
                    }
                });
                return false; // Crucial: Prevent default form submission
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
