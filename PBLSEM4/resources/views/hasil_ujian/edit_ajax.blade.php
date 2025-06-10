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
<form action="{{ url('/hasil_ujian/' . $hasil_ujian->hasil_ujian_id . '/update_ajax') }}" method="POST" id="form-edit-hasil_ujian">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Hasil Ujian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama User</label>
                    <input type="number" name="user_id" id="user_id" class="form-control"
                        value="{{ $hasil_ujian->user_id }}" required>
                    <small id="error-user_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nilai Listening</label>
                    <input type="number" name="nilai_listening" id="nilai_listening" class="form-control"
                        value="{{ $hasil_ujian->nilai_listening }}" min="0" max="495" required>
                    <small id="error-nilai_listening" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nilai Reading</label>
                    <input type="number" name="nilai_reading" id="nilai_reading" class="form-control"
                        value="{{ $hasil_ujian->nilai_reading }}" min="0" max="495" required>
                    <small id="error-nilai_reading" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nilai Total</label>
                    <input type="number" name="nilai_total" id="nilai_total" class="form-control"
                        value="{{ $hasil_ujian->nilai_listening + $hasil_ujian->nilai_reading }}" readonly>
                    <small id="error-nilai_total" class="error-text form-text text-danger"></small>
                </div>
                <script>
                $(document).ready(function() {
                    function updateNilaiTotal() {
                        var listening = parseInt($('#nilai_listening').val()) || 0;
                        var reading = parseInt($('#nilai_reading').val()) || 0;
                        $('#nilai_total').val(listening + reading);
                    }
                    $('#nilai_listening, #nilai_reading').on('input', updateNilaiTotal);
                });
                </script>
                @php
                    $total_nilai = $hasil_ujian->nilai_listening + $hasil_ujian->nilai_reading;
                    $is_lulus = $total_nilai >= 500;
                @endphp
                <div class="form-group">
                    <label>Status Lulus</label>
                    <input type="text" name="status_lulus" id="status_lulus"
                        class="form-control {{ $is_lulus ? 'bg-success text-white' : 'bg-danger text-white' }}"
                        value="{{ $is_lulus ? 'Lulus' : 'Tidak Lulus' }}" readonly>
                    <small id="error-status_lulus" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3">{{ $hasil_ujian->catatan }}</textarea>
                    <small id="error-catatan" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $("#form-edit-hasil_ujian").validate({
        rules: {
            nilai_listening: { required: true, number: true, min: 0, max: 495 },
            nilai_reading: { required: true, number: true, min: 0, max: 495 },
            status_lulus: { required: true },
            catatan: { maxlength: 255 },
            user_id: { required: true, digits: true }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: 'POST',
                data: $(form).serialize() + '&_method=PUT',
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire('Berhasil', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                        Swal.fire('Gagal', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Terjadi kesalahan AJAX', 'error');
                }
            });
            return false;
        }
    });
});
</script>
@endempty
