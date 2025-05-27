@empty($user)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data user yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/user/' . $user->user_id . '/update_ajax') }}" method="POST" id="form-edit-user">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode User</label>
                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                               class="form-control" required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama User</label>
                        <input value="{{ $user->username }}" type="text" name="username" id="username" class="form-control"
                               required>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input value="{{ $user->password }}" type="text" name="password" id="password" class="form-control"
                               required>
                        <small id="error-password" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <input value="{{ $user->profile }}" type="text" name="profile" id="profile" class="form-control"
                               required>
                        <small id="error-profile" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Role User</label>
                        <select name="role" id="role" class="form-control" required>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                        <small id="error-role" class="error-text form-text text-danger"></small>
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
        $("#form-edit-user").validate({
            rules: {
                email: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                },
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                password: {
                    required: true,
                    minlength: 5,
                    maxlength: 20
                },
                profile: {
                    required: false
                },
                role: {
                    required: true
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            // Tutup modal setelah berhasil
                            $('#modal-master').modal('hide');

                            // Menampilkan pesan sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });

                            // Reload DataTables User
                            if (typeof dataUser !== 'undefined') {
                                console.log('Reload DataTables');
                                dataUser.ajax.reload(null, true);
                            }

                            // Reset form setelah modal ditutup
                            $('#form-edit-user')[0].reset();
                            $('.error-text').text(''); // Clear error messages

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
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat memperbarui data user.'
                        });
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

        // Reset form dan hapus error saat modal ditutup
        $('#modal-master').on('hidden.bs.modal', function () {
            $('#form-edit-user')[0].reset();
            $('.error-text').text('');
            $('#form-edit-user').find('.is-invalid').removeClass('is-invalid');
        });
    });
    </script>
@endempty
