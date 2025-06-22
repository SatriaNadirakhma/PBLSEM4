@empty($user)
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
                    Data user yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('/user') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/user/' . $user->user_id . '/delete_ajax') }}" method="POST" id="form-delete-user">
        @csrf
        @method('DELETE')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi!!!</h5>
                        Apakah Anda yakin ingin menghapus data user berikut?
                    </div>
                    <table class="table table-sm table-bordered table-striped">
                        <tr>
                            <th class="text-right col-3">Email User:</th>
                            <td class="col-9">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Username:</th>
                            <td class="col-9">{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th class="text-right col-3">Foto Profil:</th>
                            <td class="col-9">
                                @php
                                    $profileUrl = $user->profile
                                        ? asset('storage/' . $user->profile)
                                        : asset('img/default-profile.png');
                                @endphp
                                <img src="{{ $profileUrl }}" alt="Foto Profil" style="max-width: 100px; max-height: 100px;" class="rounded border">
                            </td>
                        </tr></tr>
                        <tr>
                            <th class="text-right col-3">Role User:</th>
                            <td class="col-9">{{ $user->role }}</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </form>

   <script>
   $(document).ready(function() {
    // Validasi form delete user
    $("#form-delete-user").validate({
        rules: {},
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: 'POST', // gunakan POST karena DELETE disimulasikan
                data: $(form).serialize() + '&_method=DELETE',
                success: function(response) {
                    console.log(response); // Debugging response

                    if (response.status) {
                        // Menutup modal setelah penghapusan berhasil
                        $('#modal-master').modal('hide');
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });

                        // Pastikan dataUser sudah didefinisikan sebelum reload
                        if (typeof dataUser !== 'undefined') {
                            console.log('Reload DataTables');
                            dataUser.ajax.reload(null, true); // Reset DataTables dengan cara yang benar
                        } else {
                            // Jika dataUser belum didefinisikan, kita lakukan reload halaman
                            setTimeout(function() {
                                location.reload(); // Refresh halaman setelah penghapusan
                            }, 1500); // Delay 1,5 detik sebelum refresh
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat menghapus data.'
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
});
</script>
@endempty
