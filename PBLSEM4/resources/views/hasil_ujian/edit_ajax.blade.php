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
<form action="{{ url('/hasil_ujian/' . $hasil_ujian->hasil_id . '/update_ajax') }}" method="POST" id="form-edit-hasil_ujian">
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
                    <input type="text" class="form-control" value="{{ $hasil_ujian->user->nama_user }}" readonly>
                    <input type="hidden" name="user_id" value="{{ $hasil_ujian->user_id }}">
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
                <div class="form-group">
                    <label>Jadwal</label>
                    <select name="jadwal_id" id="jadwal_id" class="form-control" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach ($jadwal as $j)
                            <option value="{{ $j->jadwal_id }}" {{ $hasil_ujian->jadwal_id == $j->jadwal_id ? 'selected' : '' }}>
                                {{ $j->tanggal_pelaksanaan }} - {{ $j->jam_mulai }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-jadwal_id" class="error-text form-text text-danger"></small>
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
// Perbaikan untuk script AJAX
$(document).ready(function() {
    // Update nilai total otomatis
    function updateNilaiTotal() {
        var listening = parseInt($('#nilai_listening').val()) || 0;
        var reading = parseInt($('#nilai_reading').val()) || 0;
        var total = listening + reading;
        $('#nilai_total').val(total);
        
        // Update status lulus
        var statusLulus = total >= 500 ? 'Lulus' : 'Tidak Lulus';
        $('#status_lulus').val(statusLulus);
        $('#status_lulus').removeClass('bg-success bg-danger text-white');
        if (total >= 500) {
            $('#status_lulus').addClass('bg-success text-white');
        } else {
            $('#status_lulus').addClass('bg-danger text-white');
        }
    }
    
    $('#nilai_listening, #nilai_reading').on('input', updateNilaiTotal);

    $("#form-edit-hasil_ujian").validate({
        rules: {
            nilai_listening: { required: true, number: true, min: 0, max: 495 },
            nilai_reading: { required: true, number: true, min: 0, max: 495 },
            jadwal_id: { required: true },
            user_id: { required: true },
            catatan: { maxlength: 255 }
        },
        submitHandler: function(form) {
            // Clear previous errors
            $('.error-text').text('');
            
            // Debug: Log form action URL
            console.log('Form Action URL:', form.action);
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: form.action,
                type: 'POST', // Gunakan POST karena Laravel akan handle method override
                data: $(form).serialize(), // Ini sudah include _method=PUT dari @method('PUT')
                dataType: 'json',
                beforeSend: function() {
                    Swal.fire({
                        title: 'Menyimpan...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    Swal.close();
                    console.log('Success Response:', response);
                    
                    if(response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: true
                        }).then(() => {
                            // Reload DataTable atau halaman
                            if (typeof dataTable !== 'undefined') {
                                dataTable.ajax.reload();
                            } else {
                                location.reload();
                            }
                        });
                    } else {
                        // Show validation errors
                        if (response.msgField) {
                            $.each(response.msgField, function(key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    console.log('Error Response:', xhr.responseText);
                    console.log('Status:', xhr.status);
                    console.log('Error:', error);
                    
                    let errorMessage = 'Terjadi kesalahan pada server';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 404) {
                        errorMessage = 'Route tidak ditemukan. Periksa URL: ' + form.action;
                    } else if (xhr.status === 422) {
                        errorMessage = 'Data tidak valid';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage
                    });
                }
            });
            
            return false;
        }
    });
});
</script>
@endempty
