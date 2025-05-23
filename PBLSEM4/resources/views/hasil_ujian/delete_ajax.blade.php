@empty($hasil_ujian)
<div class="modal-dialog modal-lg" role="document">
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
<form action="{{ url('/hasil_ujian/' . $hasil_ujian->hasil_ujian_id . '/delete_ajax') }}" method="POST" id="form-delete-hasil_ujian">
    @csrf
    @method('DELETE')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Data Hasil Ujian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi</h5>
                    Apakah Anda yakin ingin menghapus data hasil ujian berikut?
                </div>
                <table class="table table-bordered table-sm">
                    <tr>
                        <th width="35%">Nilai Listening</th>
                        <td>{{ $hasil_ujian->nilai_listening }}</td>
                    </tr>
                    <tr>
                        <th>Nilai Reading</th>
                        <td>{{ $hasil_ujian->nilai_reading }}</td>
                    </tr>
                    <tr>
                        <th>Status Lulus</th>
                        <td>{{ $hasil_ujian->status_lulus }}</td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $hasil_ujian->catatan }}</td>
                    </tr>
                    <tr>
                        <th>User ID</th>
                        <td>{{ $hasil_ujian->user_id }}</td>
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
