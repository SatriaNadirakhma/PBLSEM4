@empty($prodi)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data program studi tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Program Studi</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th width="35%">ID Program Studi</th>
                    <td>{{ $prodi->prodi_id }}</td>
                </tr>
                <tr>
                    <th>Kode Program Studi</th>
                    <td>{{ $prodi->prodi_kode }}</td>
                </tr>
                <tr>
                    <th>Nama Program Studi</th>
                    <td>{{ $prodi->prodi_nama }}</td>
                </tr>
                <tr>
                    <th>Nama Jurusan</th>
                    <td>{{ $prodi->jurusan->jurusan_nama ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty
