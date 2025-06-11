<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Hasil Ujian</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <tr>
                    <th>Nama Peserta</th>
                    <td>
                        @php
                            $role = $hasil_ujian->user->role;
                            if ($role === 'mahasiswa') {
                                $nama = $hasil_ujian->user->mahasiswa->mahasiswa_nama ?? 'Tidak tersedia';
                            } elseif ($role === 'dosen') {
                                $nama = $hasil_ujian->user->dosen->dosen_nama ?? 'Tidak tersedia';
                            } elseif ($role === 'tendik') {
                                $nama = $hasil_ujian->user->tendik->tendik_nama ?? 'Tidak tersedia';
                            } else {
                                $nama = 'Tidak tersedia';
                            }
                        @endphp
                        {{ $nama }}
                    </td>
                </tr>
                <tr>
                    <th>Jadwal</th>
                    <td>{{ \Carbon\Carbon::parse($hasil_ujian->jadwal->tanggal_pelaksanaan)->format('d/m/Y') ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <th>Nilai Listening</th>
                    <td>{{ $hasil_ujian->nilai_listening }}</td>
                </tr>
                <tr>
                    <th>Nilai Reading</th>
                    <td>{{ $hasil_ujian->nilai_reading }}</td>
                </tr>
                <tr>
                    <th>Nilai Total</th>
                    <td><strong>{{ $hasil_ujian->nilai_total }}</strong></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if($hasil_ujian->status_lulus === 'lulus')
                            <span class="badge badge-success">Lulus</span>
                        @else
                            <span class="badge badge-danger">Tidak Lulus</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>{{ ucfirst($hasil_ujian->user->role) }}</td>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>