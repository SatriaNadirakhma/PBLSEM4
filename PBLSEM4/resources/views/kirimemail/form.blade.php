<form id="formKirimEmail" action="{{ route('kirimemail.kirim') }}" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $data->pendaftaran_id }}">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-envelope me-2"></i> Kirim Email
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Nama Mahasiswa</label>
                    <input
                        type="text"
                        class="form-control"
                        value="{{ $data->mahasiswa->mahasiswa_nama ?? '-' }}"
                        readonly
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ $data->mahasiswa->user->email ?? '-' }}"
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Subject</label>
                    <input
                        type="text"
                        name="subject"
                        class="form-control"
                        value="Informasi dari SIPINTA POLINEMA"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Isi Email</label>
                    @php
                        $status = $data->detail_terakhir->status ?? '-';
                        $nama   = $data->mahasiswa->mahasiswa_nama;
                        if ($status === 'diterima') {
                            $isi = "SIPINTA POLINEMA\n------------------------------\nHalo $nama, selamat!\n\nPendaftaran Anda untuk tes TOEIC telah *DITERIMA*. Silakan cek informasi di portal SIPINTA.\n\n📅 Pastikan Anda hadir tepat waktu.\n\nTerima kasih.\n— Admin SIPINTA POLINEMA";
                        } elseif ($status === 'ditolak') {
                            $isi = "SIPINTA POLINEMA\n------------------------------\nHalo $nama,\n\nMohon maaf, pendaftaran Anda untuk tes TOEIC *DITOLAK*.\n\nSilakan cek kembali data di portal SIPINTA atau hubungi admin.\n\nTerima kasih.\n— Admin SIPINTA POLINEMA";
                        } else {
                            $isi = "Halo $nama, status pendaftaran Anda: $status.";
                        }
                    @endphp
                    <textarea
                        name="pesan"
                        class="form-control"
                        rows="6"
                        required
                    >{{ $isi }}</textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane me-1"></i> Kirim
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</form>

{{-- SweetAlert2 untuk konfirmasi --}}
<script>
$('#formKirimEmail').on('submit', function(e) {
    e.preventDefault();
    const form = $(this);
    // console.log(res);

    Swal.fire({
        title: 'Kirim Email?',
        text: "Pastikan subject dan isi email sudah benar.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Kirim!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(form.attr('action'), form.serialize(), function(res) {
                Swal.fire({
                    icon: res.status === 'success' ? 'success' : 'error',
                    title: res.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    $('#myModal').modal('hide');
                    location.reload();
                });
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                });
            });
        }
    });
});
</script>
