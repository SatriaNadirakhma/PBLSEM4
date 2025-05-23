@empty($mahasiswa)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data mahasiswa tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Mahasiswa</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th width="35%">ID Mahasiswa</th>
                    <td>{{ $mahasiswa->mahasiswa_id }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $mahasiswa->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <td>{{ $mahasiswa->mahasiswa_nama }}</td>
                </tr>
                <tr>
                    <th>Angkatan</th>
                    <td>{{ $mahasiswa->angkatan }}</td>
                </tr>
                <tr>
                    <th>No Telp</th>
                    <td>{{ $mahasiswa->no_telp }}</td>
                </tr>
                <tr>
                    <th>Alamat Asal</th>
                    <td>{{ $mahasiswa->alamat_asal }}</td>
                </tr>
                <tr>
                    <th>Alamat Sekarang</th>
                    <td>{{ $mahasiswa->alamat_sekarang }}</td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $mahasiswa->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $mahasiswa->status }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $mahasiswa->keterangan }}</td>
                </tr>
                <tr>
                    <th>Prodi</th>
                    <td>{{ $mahasiswa->prodi->prodi_nama ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty
