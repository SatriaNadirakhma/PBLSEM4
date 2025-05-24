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
                    <th width="40%">Nilai Listening</th>
                    <td>{{ $hasil_ujian->nilai_listening }}</td>
                </tr>
                <tr>
                    <th>Nilai Reading</th>
                    <td>{{ $hasil_ujian->nilai_reading }}</td>
                </tr>
                <tr>
                    <th>Nilai Total</th>
                    <td>{{ $hasil_ujian->nilai_total }}</td>
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
    </div>
</div>
@endempty
