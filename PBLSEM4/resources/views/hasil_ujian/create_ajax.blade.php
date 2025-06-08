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
                            <label>Pilih Role <span class="text-danger">*</span></label>
                            <select id="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                                <option value="tendik">Tendik</option>
                            </select>
                            <small id="error-role" class="error-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Peserta <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control select2" required >
                                <option value="">-- Pilih Role Terlebih Dahulu --</option>
                                @foreach($user as $user)
                                    @if($user->role == 'mahasiswa' && $user->mahasiswa)
                                        <option value="{{ $user->id }}" data-role="mahasiswa">
                                            {{ $user->mahasiswa->mahasiswa_nama }} ({{ $user->mahasiswa->mahasiswa_nim }} - Mahasiswa)
                                        </option>
                                    @elseif($user->role == 'dosen' && $user->dosen)
                                        <option value="{{ $user->id }}" data-role="dosen">
                                            {{ $user->dosen->dosen_nama }} ({{ $user->dosen->dosen_nidn }} - Dosen)
                                        </option>
                                    @elseif($user->role == 'tendik' && $user->tendik)
                                        <option value="{{ $user->id }}" data-role="tendik">
                                            {{ $user->tendik->tendik_nama }} ({{ $user->tendik->tendik_nip }} - Tendik)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small id="error-user_id" class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
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
                                placeholder="Otomatis terhitung" data-max="990">
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
    // Inisialisasi Select2
    $('#user_id').select2({
        dropdownParent: $('#modal-master'),
        placeholder: "Pilih nama peserta...",
        allowClear: true
    });

    // Role filter logic dengan AJAX untuk fetch data
    $('#role').on('change', function() {
        const roleSelected = $(this).val();
        const userSelect = $('#user_id');
        
        if (roleSelected) {
            // Tampilkan loading
            userSelect.prop('disabled', true)
                      .html('<option value="">Memuat data...</option>')
                      .trigger('change');
            
            // AJAX request untuk mengambil data user berdasarkan role
            $.ajax({
                url: "{{ url('/get-users-by-role') }}", // Endpoint untuk mengambil user berdasarkan role
                type: 'GET',
                data: { role: roleSelected },
                dataType: 'json',
                success: function(response) {
                    userSelect.empty().append('<option value="">-- Pilih Peserta --</option>');
                    
                    if (response.status && response.data.length > 0) {
                        $.each(response.data, function(index, user) {
                            let optionText = '';
                            if (roleSelected === 'mahasiswa' && user.mahasiswa) {
                                optionText = `${user.mahasiswa.mahasiswa_nama} (${user.mahasiswa.mahasiswa_nim} - Mahasiswa)`;
                            } else if (roleSelected === 'dosen' && user.dosen) {
                                optionText = `${user.dosen.dosen_nama} (${user.dosen.dosen_nidn} - Dosen)`;
                            } else if (roleSelected === 'tendik' && user.tendik) {
                                optionText = `${user.tendik.tendik_nama} (${user.tendik.tendik_nip} - Tendik)`;
                            }
                            
                            if (optionText) {
                                userSelect.append(`<option value="${user.id}">${optionText}</option>`);
                            }
                        });
                        userSelect.prop('disabled', false);
                    } else {
                        userSelect.append('<option value="">Tidak ada data tersedia</option>');
                    }
                    userSelect.trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching users:', error);
                    userSelect.empty()
                             .append('<option value="">Error memuat data</option>')
                             .trigger('change');
                    
                    Swal.fire({
                        title: 'Error!',
                        text: 'Gagal memuat data peserta. Silakan coba lagi.',
                        icon: 'error',
                        timer: 3000
                    });
                }
            });
        } else {
            // Reset dropdown jika tidak ada role dipilih
            userSelect.prop('disabled', true)
                      .empty()
                      .append('<option value="">-- Pilih Role Terlebih Dahulu --</option>')
                      .trigger('change');
        }
    });

    // Hitung total & status kelulusan
    function calculateTotal() {
        const listening = parseInt($('#nilai_listening').val()) || 0;
        const reading = parseInt($('#nilai_reading').val()) || 0;
        let total = listening + reading;
        const maxTotal = parseInt($('#nilai_total').data('max')) || 990;
        
        if (total > maxTotal) {
            total = maxTotal;
            $('#nilai_total').addClass('border border-warning');
        } else {
            $('#nilai_total').removeClass('border border-warning');
        }
        
        $('#nilai_total').val(total);
        
        if (total >= 600) {
            $('#status_preview').val('Lulus').removeClass('text-danger').addClass('text-success');
        } else if (total > 0) {
            $('#status_preview').val('Tidak Lulus').removeClass('text-success').addClass('text-danger');
        } else {
            $('#status_preview').val('').removeClass('text-success text-danger');
        }
    }

    $('#nilai_listening, #nilai_reading').on('input change', calculateTotal);

    // Validasi & AJAX submit
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
                min: "Minimal 0",
                max: "Maksimal 495"
            },
            nilai_reading: {
                required: "Nilai reading wajib diisi",
                number: "Masukkan angka yang valid",
                min: "Minimal 0",
                max: "Maksimal 495"
            },
            catatan: { maxlength: "Maksimal 255 karakter" }
        },
        errorElement: 'small',
        errorClass: 'text-danger',
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        submitHandler: function(form) {
            $(form).find('button[type="submit"]').prop('disabled', true)
                .html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');
                
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#modal-master').modal('hide');
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        if (typeof dataHasilUjian !== 'undefined') {
                            dataHasilUjian.ajax.reload(null, false);
                        } else {
                            setTimeout(() => location.reload(), 1500);
                        }
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
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
                    console.error('Ajax Error:', xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                        icon: 'error'
                    });
                },
                complete: function() {
                    $(form).find('button[type="submit"]').prop('disabled', false)
                        .html('<i class="fa fa-save me-1"></i> Simpan Data');
                }
            });
            return false;
        }
    });

    // Reset form saat modal ditutup
    $('#modal-master').on('hidden.bs.modal', function() {
        const form = $('#form-tambah-hasil_ujian');
        form[0].reset();
        $('.error-text').text('');
        $('#role').val('');
        $('#user_id').empty()
                    .append('<option value="">-- Pilih Role Terlebih Dahulu --</option>')
                    .prop('disabled', true)
                    .trigger('change');
        $('#nilai_total, #status_preview').val('').removeClass('text-success text-danger border border-warning');
    });
});
</script>