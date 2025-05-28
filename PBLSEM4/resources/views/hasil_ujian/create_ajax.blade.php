<form action="{{ url('/hasil_ujian/ajax') }}" method="POST" id="form-tambah-hasil_ujian">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Hasil Ujian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Peserta <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control select2" required>
                                <option value="">-- Pilih Peserta --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-role="{{ $user->role }}">
                                        @if($user->mahasiswa)
                                            {{ $user->mahasiswa->mahasiswa_nama }} ({{ $user->mahasiswa->mahasiswa_nim }} - Mahasiswa)
                                        @elseif($user->dosen)
                                            {{ $user->dosen->dosen_nama }} ({{ $user->dosen->dosen_nidn }} - Dosen)
                                        @elseif($user->tendik)
                                            {{ $user->tendik->tendik_nama }} ({{ $user->tendik->tendik_nip }} - Tendik)
                                        @else
                                            {{ $user->nama ?? $user->username }} ({{ ucfirst($user->role) }}) 
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-user_id" class="error-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Jadwal Ujian <span class="text-danger">*</span></label>
                            <select name="jadwal_id" id="jadwal_id" class="form-control" required>
                                <option value="">-- Pilih Jadwal --</option>
                                @foreach($jadwal as $j)
                                    <option value="{{ $j->jadwal_id }}">
                                        {{ \Carbon\Carbon::parse($j->tanggal_pelaksanaan)->format('d/m/Y') }} - 
                                        {{ $j->waktu_mulai }} s/d {{ $j->waktu_selesai }}
                                        @if($j->lokasi)
                                            ({{ $j->lokasi }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <small id="error-jadwal_id" class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nilai Listening <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_listening" id="nilai_listening" 
                                   class="form-control" min="0" max="495" required
                                   placeholder="Masukkan nilai listening (0-495)">
                            <small class="form-text text-muted">Rentang nilai: 0 - 495</small>
                            <small id="error-nilai_listening" class="error-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nilai Reading <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_reading" id="nilai_reading" 
                                   class="form-control" min="0" max="495" required
                                   placeholder="Masukkan nilai reading (0-495)">
                            <small class="form-text text-muted">Rentang nilai: 0 - 495</small>
                            <small id="error-nilai_reading" class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Nilai</label>
                            <input type="number" id="nilai_total" class="form-control" readonly 
                                   placeholder="Otomatis terhitung">
                            <small class="form-text text-muted">Total otomatis = Listening + Reading</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status Kelulusan</label>
                            <input type="text" id="status_preview" class="form-control" readonly 
                                   placeholder="Otomatis berdasarkan total nilai">
                            <small class="form-text text-muted">Lulus jika total ≥ 600</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3" 
                              placeholder="Tambahkan catatan atau komentar (opsional)"></textarea>
                    <small id="error-catatan" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">
                    <i class="fa fa-times me-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Initialize Select2 untuk dropdown peserta
    $('#user_id').select2({
        dropdownParent: $('#modal-master'),
        placeholder: "Cari nama peserta...",
        allowClear: true
    });

    // Auto calculate total nilai dan status
    function calculateTotal() {
        const listening = parseInt($('#nilai_listening').val()) || 0;
        const reading = parseInt($('#nilai_reading').val()) || 0;
        const total = listening + reading;
        
        $('#nilai_total').val(total);
        
        if (total >= 600) {
            $('#status_preview').val('Lulus').removeClass('text-danger').addClass('text-success');
        } else if (total > 0) {
            $('#status_preview').val('Tidak Lulus').removeClass('text-success').addClass('text-danger');
        } else {
            $('#status_preview').val('').removeClass('text-success text-danger');
        }
    }

    // Event listeners untuk auto calculate
    $('#nilai_listening, #nilai_reading').on('input change', calculateTotal);

    // Form validation
    $("#form-tambah-hasil_ujian").validate({
        rules: {
            user_id: { required: true },
            jadwal_id: { required: true },
            nilai_listening: { required: true, number: true, min: 0, max: 495 },
            nilai_reading: { required: true, number: true, min: 0, max: 495 },
            catatan: { maxlength: 255 }
        },
        messages: {
            user_id: "Pilih peserta terlebih dahulu",
            jadwal_id: "Pilih jadwal ujian terlebih dahulu",
            nilai_listening: {
                required: "Nilai listening wajib diisi",
                number: "Masukkan angka yang valid",
                min: "Nilai minimal 0",
                max: "Nilai maksimal 495"
            },
            nilai_reading: {
                required: "Nilai reading wajib diisi",
                number: "Masukkan angka yang valid",
                min: "Nilai minimal 0",
                max: "Nilai maksimal 495"
            },
            catatan: {
                maxlength: "Catatan maksimal 255 karakter"
            }
        },
        errorElement: 'small',
        errorClass: 'text-danger',
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            // Disable submit button
            $(form).find('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');
            
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Reload DataTable
                        if (typeof dataHasilUjian !== 'undefined') {
                            dataHasilUjian.ajax.reload(null, false);
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    } else {
                        // Clear previous errors
                        $('.error-text').text('');
                        
                        // Show field errors
                        if(response.msgField) {
                            $.each(response.msgField, function(key, val) {
                                $('#error-' + key).text(Array.isArray(val) ? val[0] : val);
                            });
                        }
                        
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message || 'Gagal menyimpan data',
                            icon: 'error'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Ajax Error:', xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                        icon: 'error'
                    });
                },
                complete: function() {
                    // Re-enable submit button
                    $(form).find('button[type="submit"]').prop('disabled', false).html('<i class="fa fa-save me-1"></i> Simpan Data');
                }
            });
            return false;
        }
    });

    // Reset form when modal is hidden
    $('#modal-master').on('hidden.bs.modal', function() {
        $('#form-tambah-hasil_ujian')[0].reset();
        $('.error-text').text('');
        $('#user_id').val(null).trigger('change');
        $('#nilai_total, #status_preview').val('').removeClass('text-success text-danger');
    });
});
</script>

<style>
.form-group label {
    font-weight: 600;
    color: #495057;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.select2-container--default .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
}

.text-success {
    color: #28a745 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.form-text {
    font-size: 0.875em;
}
</style>