<form id="formKirimPesan" action="{{ url('kirimpesan/kirim') }}" method="POST">
    @csrf
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fab fa-whatsapp mr-2"></i>Kirim Pesan WhatsApp</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Mahasiswa</label>
                    <input type="text" class="form-control" value="{{ $data->mahasiswa->mahasiswa_nama }}" readonly>
                </div>

                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <input type="text" name="nomor" class="form-control" value="{{ $data->mahasiswa->no_telp }}" readonly>
                </div>

                <div class="form-group">
                    <label>Isi Pesan</label>
                    @php
                        $status = $data->detail_terakhir->status ?? '-';
                        $nama = $data->mahasiswa->mahasiswa_nama;
                        if ($status === 'diterima') {
                            $pesan = "SIPINTA POLINEMA\n------------------------------\nHalo $nama, selamat!\n\nPendaftaran Anda untuk tes TOEIC telah *DITERIMA*. Silakan cek informasi lebih lanjut melalui portal SIPINTA.\n\n📅 Pastikan Anda mengikuti jadwal tes dengan tepat waktu.\n📌 Bawa dokumen yang diperlukan saat hari pelaksanaan.\n\nTerima kasih.\n— Admin SIPINTA POLINEMA";
                        } elseif ($status === 'ditolak') {
                            $pesan = "SIPINTA POLINEMA\n------------------------------\nHalo $nama,\n\nMohon maaf, pendaftaran Anda untuk tes TOEIC telah *DITOLAK*.\n\nSilakan cek kembali data yang Anda kirimkan di portal SIPINTA atau hubungi admin untuk informasi lebih lanjut.\n\nTerima kasih atas pengertiannya.\n— Admin SIPINTA POLINEMA";
                        } else {
                            $pesan = "Halo $nama, status pendaftaran Anda: $status.";
                        }
                    @endphp
                    <textarea name="pesan" class="form-control" rows="7" required>{{ $pesan }}</textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane mr-1"></i> Kirim</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</form>

<!-- SweetAlert2 -->
<script>
    $('#formKirimPesan').on('submit', function (e) {
        e.preventDefault();
        const form = $(this);
        Swal.fire({
            title: 'Kirim Pesan?',
            text: "Pastikan isi pesan sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(form.attr('action'), form.serialize(), function (res) {
                    Swal.fire({
                        icon: res.status === 'success' ? 'success' : 'error',
                        title: res.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        $('#myModal').modal('hide');
                        location.reload();
                    });
                }).fail(function (xhr) {
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
