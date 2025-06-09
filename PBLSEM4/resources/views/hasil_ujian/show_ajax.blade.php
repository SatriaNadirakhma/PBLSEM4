@empty($hasil_ujian)
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data hasil ujian tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Hasil Ujian</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-striped table-sm">
                <tr>
                    <th width="40%">Nama Peserta</th>
                    <td>{{ $hasil_ujian->user->nama ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $hasil_ujian->user->username ?? 'Tidak tersedia' }}</td>
                </tr>
                @if($hasil_ujian->user->mahasiswa)
                <tr>
                    <th>NIM</th>
                    <td>{{ $hasil_ujian->user->mahasiswa->nim }}</td>
                </tr>
                @endif
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
                    <th>Status Lulus</th>
                    <td>
                        @if($hasil_ujian->status_lulus == 'lulus')
                            <span class="badge badge-success">{{ $hasil_ujian->status_lulus }}</span>
                        @else
                            <span class="badge badge-danger">{{ $hasil_ujian->status_lulus }}</span>
                        @endif
                    </td>
                </tr>
                @if($hasil_ujian->catatan)
                <tr>
                    <th>Catatan</th>
                    <td>{{ $hasil_ujian->catatan }}</td>
                </tr>
                @endif
                @if($hasil_ujian->jadwal)
                <tr>
                    <th>Jadwal Ujian</th>
                    <td>{{ $hasil_ujian->jadwal->mata_kuliah ?? 'Tidak tersedia' }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $hasil_ujian->jadwal->tanggal ?? 'Tidak tersedia' }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>
</div>
@endempty