@empty($jurusan)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/jurusan') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/jurusan/' . $jurusan->jurusan_id . '/update_ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Jurusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode Jurusan</label>
                        <input value="{{ $jurusan->jurusan_kode }}" type="text" name="jurusan_kode" id="jurusan_kode"
                            class="form-control" required>
                        <small id="error-jurusan_kode" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Jurusan</label>
                        <input value="{{ $jurusan->jurusan_nama }}" type="text" name="jurusan_nama" id="jurusan_nama"
                            class="form-control" required>
                        <small id="error-jurusan_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama Kampus</label>
                        <select name="kampus_id" id="kampus_id" class="form-control" required>
                            <option value="">- Pilih Kampus -</option>
                            @foreach ($kampus as $k)
                                <option value="{{ $k->kampus_id }}" {{ $jurusan->kampus_id == $k->kampus_id ? 'selected' : '' }}>
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
                    jurusan_kode: {
                        required: true,
                        minlength: 2,
                        maxlength: 20
                    },
                    jurusan_nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 100
                    },
                    kampus_id: {
                        required: true
                    },
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: form.action,
                        type: 'POST',  // tetap POST, tapi dengan _method=PUT
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
