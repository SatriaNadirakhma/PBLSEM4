@empty($pendaftaran)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data pendaftaran tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header bg-info text-white">
            <h5 class="modal-title">Detail Pendaftaran Mahasiswa</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span class="text-white">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th width="35%">Kode Pendaftaran</th>
                    <td>{{ $pendaftaran->pendaftaran_kode }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pendaftaran</th>
                    <td>
                        {{ $pendaftaran->tanggal_pendaftaran
                            ? \Carbon\Carbon::parse($pendaftaran->tanggal_pendaftaran)->format('d-m-Y - H:i:s')
                            : '-' }}
                    </td>
                </tr>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <td>{{ $pendaftaran->mahasiswa->mahasiswa_nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $pendaftaran->mahasiswa->nim ?? '-' }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $pendaftaran->mahasiswa->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <th>No. Telp</th>
                    <td>{{ $pendaftaran->mahasiswa->no_telp ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat Asal</th>
                    <td>{{ $pendaftaran->mahasiswa->alamat_asal ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat Sekarang</th>
                    <td>{{ $pendaftaran->mahasiswa->alamat_sekarang ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Program Studi</th>
                    <td>{{ $pendaftaran->mahasiswa->prodi->prodi_nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jurusan</th>
                    <td>{{ $pendaftaran->mahasiswa->prodi->jurusan->jurusan_nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Kampus</th>
                    <td>{{ $pendaftaran->mahasiswa->prodi->jurusan->kampus->kampus_nama ?? '-' }}</td>
                </tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ ucfirst($pendaftaran->mahasiswa->jenis_kelamin ?? '-') }}</td>
                </tr>
                 <tr>
                    <th>Scan KTP</th>
                    <td>
                        @if($pendaftaran->scan_ktp)
                            <a href="{{ asset('storage/' . $pendaftaran->scan_ktp) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pendaftaran->scan_ktp) }}" class="img-thumbnail" width="150">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Scan KTM</th>
                    <td>
                        @if($pendaftaran->scan_ktm)
                            <a href="{{ asset('storage/' . $pendaftaran->scan_ktm) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pendaftaran->scan_ktm) }}" class="img-thumbnail" width="150">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Pas Foto</th>
                    <td>
                        @if($pendaftaran->pas_foto)
                            <a href="{{ asset('storage/' . $pendaftaran->pas_foto) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pendaftaran->pas_foto) }}" class="img-thumbnail" width="150">
                            </a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Jadwal yang Dipilih</th>
                    <td>
                        {{ $pendaftaran->jadwal->tanggal_pelaksanaan 
                            ? \Carbon\Carbon::parse($pendaftaran->jadwal->tanggal_pelaksanaan)->format('d-m-Y') 
                            : '-' }} - {{ $pendaftaran->jadwal->jam_mulai ?? '-' }}
                    </td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty