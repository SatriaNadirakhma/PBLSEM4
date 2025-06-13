@empty($hasil_ujian)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data hasil ujian yang Anda cari tidak ditemukan.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
@else
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                    Apakah Anda yakin ingin menghapus data hasil ujian berikut?
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-sm table-bordered table-striped">
                            <tr>
                                <th class="text-right col-4">ID Hasil</th>
                                <td class="col-8">{{ $hasil_ujian->hasil_id }}</td>
                            </tr>
                            <tr>
                                <th class="text-right col-4">Status</th>
                                <td class="col-8">{{ $hasil_ujian->user->role }}</td>
                            </tr>
                           <tr>
                                    <th class="text-right">Nama Peserta</th>
                                    <td>
                                        @php
                                            $user = $hasil_ujian->user;
                                            $nama = '-';

                                            if ($user->role == 'mahasiswa' && isset($user->mahasiswa)) {
                                                $nama = $user->mahasiswa->mahasiswa_nama;
                                            } elseif ($user->role == 'dosen' && isset($user->dosen)) {
                                                $nama = $user->dosen->dosen_nama;
                                            } elseif ($user->role == 'tendik' && isset($user->tendik)) {
                                                $nama = $user->tendik->tendik_nama;
                                            } else {
                                                $nama = $user->nama ?? '-';
                                            }
                                        @endphp
                                        {{ $nama }}
                                    </td>
                                </tr>  
                            <tr>
                            <th class="text-right">ID Peserta</th>
                                <td>
                                     @php
                                        $user = $hasil_ujian->user;
                                        $Id = '-';

                                        if ($user->role == 'mahasiswa' && isset($user->mahasiswa)) {
                                            $Id = $user->mahasiswa->nim;
                                        } elseif ($user->role == 'dosen' && isset($user->dosen)) {
                                            $Id = $user->dosen->nidn;
                                        } elseif ($user->role == 'tendik' && isset($user->tendik)) {
                                            $Id = $user->tendik->nip;
                                        } else {
                                            $Id = $user->Id ?? '-';
                                        }
                                    @endphp
                                    {{ $Id }}
                                </td>
                            </tr>   
                            <tr>
                                <th class="text-right">Nilai Listening</th>
                                <td>{{ $hasil_ujian->nilai_listening }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">Nilai Reading</th>
                                <td>{{ $hasil_ujian->nilai_reading }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">Status Lulus</th>
                                <td>
                                    <span class="badge {{ $hasil_ujian->status_lulus == 'Lulus' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $hasil_ujian->status_lulus }}
                                    </span>
                                </td>
                            </tr>
                            @if($hasil_ujian->jadwal)
                                <tr>
                                    <th class="text-right">Jadwal Ujian</th>
                                    <td>{{ $hasil_ujian->jadwal->tanggal_pelaksanaan ?? '-' }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="alert alert-info">
                    <small><i class="fas fa-info-circle"></i> Data yang sudah dihapus tidak dapat dikembalikan!</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete" data-id="{{ $hasil_ujian->hasil_id }}">
                    <i class="fas fa-trash"></i> Ya, Hapus Data
                </button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#btn-confirm-delete').on('click', function() {
                let hasil_id = $(this).data('id');
                let $btn = $(this);

                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menghapus...');

                $.ajax({
                    url: '/hasil_ujian/' + hasil_id + '/delete_ajax',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#modal-confirm-delete').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Data berhasil dihapus',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            if (typeof dataHasil !== 'undefined' && dataHasil.ajax) {
                                dataHasil.ajax.reload(null, false);
                            } else if (typeof table !== 'undefined' && table.ajax) {
                                table.ajax.reload(null, false);
                            } else {
                                setTimeout(() => location.reload(), 2000);
                            }
                        } else {
                            $btn.prop('disabled', false).html('<i class="fas fa-trash"></i> Ya, Hapus Data');

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan saat menghapus data'
                            });
                        }
                    },
                    error: function(xhr) {
                        $btn.prop('disabled', false).html('<i class="fas fa-trash"></i> Ya, Hapus Data');

                        let errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus data';

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    }
                });
            });
        });
    </script>
@endempty
