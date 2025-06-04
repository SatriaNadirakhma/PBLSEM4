@empty($informasi)
<div class="modal-dialog" role="document">
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
<div id="modal-master" class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Informasi</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-striped table-sm">
                <tr>
                    <th width="35%">Judul</th>
                    <td>{{ $informasi->judul }}</td>
                </tr>
                <tr>
                    <th>Isi</th>
                    <td>{!! $informasi->isi !!}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endempty
