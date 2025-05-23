@empty($jadwal)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data jadwal tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<form action="{{ url('/jadwal/' . $jadwal->jadwal_id . '/delete_ajax') }}" method="POST" id="form-delete-jadwal">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi</h5>
                    Apakah Anda yakin ingin menghapus jadwal berikut?
                </div>
                <table class="table table-bordered table-sm">
                    <tr>
                        <th width="35%">Tanggal Pelaksanaan</th>
                        <td>{{ $jadwal->tanggal_pelaksanaan }}</td>
                    </tr>
                    <tr>
                        <th>Jam Mulai</th>
                        <td>{{ $jadwal->jam_mulai }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $jadwal->keterangan }}</td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
