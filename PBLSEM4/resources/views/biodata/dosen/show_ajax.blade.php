@empty($dosen)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data dosen tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Dosen</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th>NIDN</th>
                    <td>{{ $dosen->nidn }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $dosen->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Dosen</th>
                    <td>{{ $dosen->dosen_nama }}</td>
                </tr>
                <tr>
                    <th>No Telp</th>
                    <td>{{ $dosen->no_telp }}</td>
                </tr>
                <tr>
                    <th>Alamat Asal</th>
                    <td>{{ $dosen->alamat_asal }}</td>
                </tr>
                <tr>
                    <th>Alamat Sekarang</th>
                    <td>{{ $dosen->alamat_sekarang }}</td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $dosen->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <th>Jurusan</th>
                    <td>{{ $dosen->jurusan->jurusan_nama ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty
