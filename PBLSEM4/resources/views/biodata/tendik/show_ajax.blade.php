@empty($tendik)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data tendik tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Tendik</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th width="35%">ID Tendik</th>
                    <td>{{ $tendik->tendik_id }}</td>
                </tr>
                <tr>
                    <th>NIP</th>
                    <td>{{ $tendik->nip }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $tendik->nik }}</td>
                </tr>
                <tr>
                    <th>Nama Tendik</th>
                    <td>{{ $tendik->tendik_nama }}</td>
                </tr>
                <tr>
                    <th>No Telp</th>
                    <td>{{ $tendik->no_telp }}</td>
                </tr>
                <tr>
                    <th>Alamat Asal</th>
                    <td>{{ $tendik->alamat_asal }}</td>
                </tr>
                <tr>
                    <th>Alamat Sekarang</th>
                    <td>{{ $tendik->alamat_sekarang }}</td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $tendik->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <th>Kampus Nama</th>
                    <td>{{ $tendik->kampus->kampus_nama }}</td>
                </tr>
            </table>
        </div>
        <!-- <td>{{ $prodi->jurusan->jurusan_nama ?? '-' }}</td> -->
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty
