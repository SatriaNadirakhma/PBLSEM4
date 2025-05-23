@empty($informasi)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data informasi tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/informasi/' . $informasi->informasi_id . '/delete_ajax') }}" method="POST" id="form-delete-informasi">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Informasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi</h5>
                    Apakah Anda yakin ingin menghapus informasi berikut?
                </div>
                <table class="table table-bordered table-sm">
                    <tr>
                        <th width="35%">Judul</th>
                        <td>{{ $informasi->judul }}</td>
                    </tr>
                    <tr>
                        <th>Isi</th>
                        <td>{{ $informasi->isi }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</form>
@endempty
