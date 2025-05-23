<form action="{{ url('/user/ajax') }}" method="POST" id="form-tambah-user" enctype="multipart/form-data">
    @csrf
    <div id="modal-tambah-user" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="role" class="form-control" required>
                        <option value="" selected disabled>-- Pilih Role --</option>
                        <option value="admin">Admin</option>
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                        <option value="tendik">Tendik</option>
                    </select>
                    <small id="error-role" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <select name="role_related_id" id="nama_lengkap" class="form-control" disabled required>
                        <option value="" selected disabled>-- Pilih Role dulu --</option>
                    </select>
                    <small id="error-role_related_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                    <small id="error-username" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Password </label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Foto Profil <small class="text-muted"> (Optional)</small></label>
                    <input type="file" name="profile" id="profile" class="form-control" accept="image/*">
                    <small id="error-profile" class="error-text form-text text-danger"></small>
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
        $('#role').on('change', function() {
            let role = $(this).val();
            let namaSelect = $('#nama_lengkap');
            if(!role){
                namaSelect.html('<option value="" selected disabled>-- Pilih Role dulu --</option>').attr('disabled', true);
                return;
            }

            // AJAX ambil nama lengkap berdasar role (ambil id dan nama lengkap)
           $.ajax({
                    url: `{{ url('user/get-nama-by-role') }}/${role}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        namaSelect.removeAttr('disabled');
                        namaSelect.attr('name', role + '_id');
                        namaSelect.html('<option value="" selected disabled>-- Pilih Nama Lengkap --</option>');

                        if (data.length === 0) {
                            namaSelect.html('<option value="" selected disabled>Tidak ada data tersedia</option>');

                            Swal.fire({
                                icon: 'warning',
                                title: 'Biodata Tidak Tersedia',
                                text: 'Semua biodata untuk role ini sudah digunakan. Silakan tambahkan biodata baru terlebih dahulu untuk menambahkan data user.',
                                confirmButtonText: 'Ok'
                            });
                        } else {
                            $('#error-role_related_id').text('');
                            $.each(data, function(i, item) {
                                namaSelect.append(`<option value="${item.id}">${item.nama_lengkap}</option>`);
                            });
                        }

                        // Kosongkan username jika admin
                        if(role === 'admin') {
                            $('#username').val('');
                        }
                    },
                    error: function(xhr) {
                        namaSelect.html('<option value="" selected disabled>Error mengambil data</option>');
                        console.error('AJAX ERROR:', xhr.responseText);
                    }
                });
            });

            // Saat pilih nama lengkap, ambil detail untuk isi username otomatis
            $('#nama_lengkap').on('change', function() {
                let role = $('#role').val();
                let selectedId = $(this).val();

                if (!selectedId || !role || role === 'admin') return;

                $.ajax({
                    url: `{{ url('user/get-detail-by-role') }}/${role}/${selectedId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Asumsikan data berisi nim/nidn/nip tergantung role
                        let username = '';
                        if (role === 'mahasiswa') {
                            username = data.nim;
                        } else if (role === 'dosen') {
                            username = data.nidn;
                        } else if (role === 'tendik') {
                            username = data.nip;
                        }
                        $('#username').val(username); // tetap bisa diedit
                    },
                    error: function(xhr) {
                        console.error('Gagal mengambil detail user:', xhr.responseText);
                    }
                });
            });

        $("#form-tambah-user").validate({
            rules: {
                role: { required: true },
                role_related_id: { required: true },
                username: { required: true, minlength: 3 },
                password: { minlength: 5 },
                profile: { extension: "jpg|jpeg|png" }
            },
            messages: {
                profile: { extension: "Format file harus jpg, jpeg, atau png" }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                let id = element.attr('id');
                $('#error-' + id).text(error.text());
            },
            success: function(label, element) {
                let id = $(element).attr('id');
                $('#error-' + id).text('');
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                let formData = new FormData(form);
                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.status) {
                            $('#modal-tambah-user').modal('hide');
                            $('.error-text').text('');
                            form.reset();

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
            }
        });
    });
</script>
