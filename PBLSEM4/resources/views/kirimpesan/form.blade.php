<form id="formKirimPesan" action="{{ url('kirimpesan/kirim') }}" method="POST">
    @csrf
    <input type="hidden" name="pendaftaran_id" value="{{ $data->pendaftaran_id }}">
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
                    <label for="nomorWhatsapp">Nomor WhatsApp</label>
                    <input type="text" name="nomor" id="nomorWhatsapp" class="form-control" value="{{ $data->mahasiswa->no_telp }}"
                           pattern="[0-9]*"
                           inputmode="numeric">
                    {{-- Tambahkan elemen untuk pesan error --}}
                    <small id="nomorError" class="text-danger" style="display: none;">Hanya boleh diisi angka.</small>
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

<script>
    $(document).ready(function() {
        const nomorInput = $('#nomorWhatsapp');
        const nomorError = $('#nomorError');

        // Fungsi untuk validasi dan menampilkan/menyembunyikan pesan error
        function validateNomorInput() {
            const value = nomorInput.val();
            // Periksa apakah nilai mengandung karakter non-angka
            if (/[^0-9]/.test(value)) {
                nomorError.show(); // Tampilkan pesan error
                nomorInput.addClass('is-invalid'); // Tambahkan kelas validasi Bootstrap
                return false; // Validasi gagal
            } else {
                nomorError.hide(); // Sembunyikan pesan error
                nomorInput.removeClass('is-invalid'); // Hapus kelas validasi Bootstrap
                return true; // Validasi berhasil
            }
        }

        // Jalankan validasi saat input berubah
        nomorInput.on('input', function() {
            validateNomorInput();
        });

        // Jalankan validasi saat form disubmit
        $('#formKirimPesan').on('submit', function (e) {
            // Pastikan validasi nomor berhasil sebelum melanjutkan submit
            if (!validateNomorInput()) {
                e.preventDefault(); // Mencegah form disubmit jika ada error
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Input',
                    text: 'Nomor WhatsApp hanya boleh diisi angka.',
                });
                return; // Berhenti eksekusi fungsi
            }

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
                            $(document).trigger('whatsappSent', {
                                pendaftaran_id: res.pendaftaran_id,
                                status: res.status,
                                message: res.message,
                                pengiriman_status: res.pengiriman_status
                            });
                        });
                    }).fail(function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                        }).then(() => {
                            $(document).trigger('whatsappSent', {
                                pendaftaran_id: form.find('input[name="pendaftaran_id"]').val(),
                                status: 'error',
                                message: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                                pengiriman_status: 'gagal'
                            });
                        });
                    });
                }
            });
        });
    });
</script>