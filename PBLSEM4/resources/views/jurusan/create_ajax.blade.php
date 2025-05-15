<form action="{{ url('/jurusan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Jurusan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Kampus</label>
                    <select name="kampus_id" id="kampus_id" class="form-control" required>
                        <option value="">- Pilih Kampus -</option>
                        @foreach ($kampus as $k)
                            <option value="{{ $k->kampus_id }}">{{ $k->kampus_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kampus_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Jurusan</label>
                    <input type="text" name="jurusan_kode" id="jurusan_kode" class="form-control" required>
                    <small id="error-jurusan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Jurusan</label>
                    <input type="text" name="jurusan_nama" id="jurusan_nama" class="form-control" required>
                    <small id="error-jurusan_nama" class="error-text form-text text-danger"></small>
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
        $("#form-tambah").validate({
            rules: {
                kampus_id: {
                    required: true,
                    number: true
                },
                jurusan_kode: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                jurusan_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 100
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataJurusan.ajax.reload(); // sesuaikan dengan DataTable jurusan
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
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
