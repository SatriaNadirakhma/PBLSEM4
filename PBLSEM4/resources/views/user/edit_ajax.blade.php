@empty($user)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
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
    <form action="{{ url('/user/' . $user->user_id . '/update_ajax') }}" method="POST" id="form-edit-user" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="role" value="{{ $user->role }}">
        <input type="hidden" name="role_related_id" value="{{ $user->role_related_id }}">
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Kotak Informasi -->
                    <div class="alert alert-warning" role="alert">
                        <strong>Informasi:</strong> Jika ingin mengubah role dan nama, harus diganti di menu Biodata User. Password hanya diubah oleh User yang bersangkutan.
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select id="role" class="form-control" disabled>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="mahasiswa" {{ $user->role == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="dosen" {{ $user->role == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="tendik" {{ $user->role == 'tendik' ? 'selected' : '' }}>Tendik</option>
                        </select>
                        <small id="error-role" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <select id="nama_lengkap" class="form-control" disabled>
                            <option value="{{ $user->role_related_id }}" selected>{{ $user->nama_lengkap }}</option>
                        </select>
                        <small id="error-role_related_id" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="{{ $user->username }}" required>
                        <small id="error-username" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Password </label>
                        <input type="password" name="password" id="password" class="form-control" readonly value="*****">
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
            $("#form-edit-user").validate({
                rules: {
                    username: { required: true, minlength: 3 },
                    email: { required: true, email: true },
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
                            if (response.status) {
                                $('#modal-master').modal('hide');
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

            $('#modal-master').on('hidden.bs.modal', function() {
                $('#form-edit-user')[0].reset();
                $('.error-text').text('');
                $('#form-edit-user').find('.is-invalid').removeClass('is-invalid');
            });
        });
    </script>
@endempty